<?php

namespace App\Livewire\Pages\Userpages\Question;

use App\Models\AiGeneration;
use App\Models\Question;
use App\Models\QuestionSet;
use App\Models\SubjectPrompt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class GeneratedQuestions extends Component
{
    public $questions = [];
    public $isLoading = false;
    public $error = null;

    public $subject = 'General';
    public $subjects = ['General'];
    public $newSubject = '';

    public $difficulty = 'Easy';
    public $count = 10;

    public $questionSetId = null;
    public $isAddingToExisting = false;

    public $editingIndex = null;
    public $editingQuestion = [
        'question' => '',
        'answers' => [],
        'correctAnswer' => null,
    ];

    public function editQuestion($index)
    {
        if (!isset($this->questions[$index])) {
            return;
        }

        $this->editingIndex = $index;
        $this->editingQuestion = $this->questions[$index];

        if (!is_array($this->editingQuestion['answers'])) {
            $this->editingQuestion['answers'] = [];
        }

        // Ensure there are at least 4 answer slots for editing convenience
        $this->editingQuestion['answers'] = array_values(array_pad($this->editingQuestion['answers'], 4, ''));
    }

    public function cancelEdit()
    {
        $this->editingIndex = null;
        $this->editingQuestion = [
            'question' => '',
            'answers' => [],
            'correctAnswer' => null,
        ];
    }

    public function saveEdit()
    {
        if ($this->editingIndex === null || !isset($this->questions[$this->editingIndex])) {
            return;
        }

        $index = $this->editingIndex;
        $this->questions[$index] = [
            'question' => trim($this->editingQuestion['question']) ?: $this->questions[$index]['question'],
            'answers' => array_values(array_filter(array_map('trim', $this->editingQuestion['answers']))),
            'correctAnswer' => trim((string) $this->editingQuestion['correctAnswer']),
        ];

        if ($this->questionSetId) {
            $set = QuestionSet::find($this->questionSetId);
            if ($set) {
                $question = $set->questions()->orderBy('id')->skip($index)->first();
                if ($question) {
                    $question->update([
                        'question' => $this->questions[$index]['question'],
                        'correct_answer' => $this->questions[$index]['correctAnswer'],
                    ]);

                    // Recreate options
                    $question->options()->delete();
                    foreach ($this->questions[$index]['answers'] as $answerText) {
                        $question->options()->create([
                            'option_text' => $answerText,
                            'is_correct' => strtolower($answerText) === strtolower($this->questions[$index]['correctAnswer']),
                        ]);
                    }
                }
            }
        }

        $this->cancelEdit();
    }

    public function delete($index)
    {
        if ($this->questionSetId && is_int($index)) {
            $set = QuestionSet::find($this->questionSetId);
            if ($set) {
                $question = $set->questions()->orderBy('id')->skip($index)->first();
                if ($question) {
                    $question->options()->delete();
                    $question->delete();
                    $set->total_questions = $set->questions()->count();
                    $set->save();
                }
            }
        }

        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);

        if ($this->editingIndex === $index) {
            $this->cancelEdit();
        }
    }

    /* ---------------------------
        TITLE (computed)
    ----------------------------*/
    public function formTitle()
    {
        return sprintf('%s - %s', implode(', ', $this->subjects), ucfirst($this->difficulty));
    }

    public function formDescription()
    {
        return sprintf(
            'AI-generated questions for %s at %s difficulty.',
            implode(', ', $this->subjects),
            $this->difficulty
        );
    }

    /* ---------------------------
        MOUNT
    ----------------------------*/
    public function mount()
    {
        $id = request()->query('question_set');

        if ($id) {
            $this->questionSetId = $id;
            $this->loadSaved($id);
        }
    }

    /* ---------------------------
        LOAD EXISTING SET
    ----------------------------*/
    public function loadSaved($id)
    {
        $set = QuestionSet::with(['questions.options', 'subjectPrompts'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$set) return;

        $this->questionSetId = $set->id;

        // Prefer subjects coming from SubjectPrompts (they are authoritative per-question topics)
        $promptSubjects = $set->subjectPrompts->pluck('subject')->filter()->unique()->values()->toArray();
        if (!empty($promptSubjects)) {
            $this->subjects = $promptSubjects;
        } else {
            $this->subjects = $set->subjects ?? ['General'];
        }

        $this->subject = $this->subjects[0] ?? 'General';
        $this->difficulty = ucfirst($set->difficulty);

        $this->questions = $set->questions->map(function ($q) {
            return [
                'question' => $q->question,
                'answers' => $q->options->pluck('option_text')->toArray(),
                'correctAnswer' => $q->correct_answer,
            ];
        })->toArray();
    }

    /* ---------------------------
        GENERATE EVENT
    ----------------------------*/
    #[On('generate-exam')]
    public function loadQuestions(
        $subject = 'General',
        $difficulty = 'Easy',
        $count = 10,
        $questionSetId = null,
        $isAddingToExisting = false
    ) {
        if (is_array($subject)) {
            $p = $subject;
            $subject = $p['subject'] ?? 'General';
            $difficulty = $p['difficulty'] ?? 'Easy';
            $count = $p['count'] ?? 10;
            $questionSetId = $p['questionSetId'] ?? null;
            $isAddingToExisting = $p['isAddingToExisting'] ?? false;
        }

        $this->subject = $subject;
        $this->difficulty = $difficulty;
        $this->count = (int) $count;

        // If adding to an existing question set, load it (including its SubjectPrompts)
        if ($isAddingToExisting && $questionSetId) {
            $this->questionSetId = $questionSetId;
            $this->loadSaved($questionSetId);

            // ensure the requested generation subject is present in the subjects list
            if (!in_array($subject, $this->subjects)) {
                $this->subjects[] = $subject;
            }
        } else {
            $this->subjects = [$subject];
        }

        $this->isLoading = true;
        $this->error = null;

        $this->dispatch('puter-generate',
            subject: $subject,
            difficulty: $difficulty,
            count: $count
        );
    }

    /* ---------------------------
        SAVE QUESTIONS
    ----------------------------*/
    protected function getQuestionSet(): QuestionSet
    {
        // ONLY source of truth = ID
        if ($this->questionSetId) {
            return QuestionSet::findOrFail($this->questionSetId);
        }

        $set = QuestionSet::create([
            'user_id' => Auth::id(),
            'title' => $this->formTitle(),
            'subjects' => $this->subjects,
            'difficulty' => strtolower($this->difficulty),
            'description' => $this->formDescription(),
            'is_ai_generated' => true,
            'status' => 'draft',
            'total_questions' => 0,
        ]);

        $this->questionSetId = $set->id;

        return $set;
    }

    /* ---------------------------
        HANDLE GENERATED QUESTIONS
    ----------------------------*/
    #[On('questionsGenerated')]
    public function handleQuestionsGenerated($questions = [])
    {
        $questions = $questions['questions'] ?? $questions;

        $set = $this->getQuestionSet();
        
        DB::transaction(function () use ($questions, $set) {

            // Ensure there's a SubjectPrompt for this QuestionSet + subject
            $subjectKey = trim($this->subject) ?: 'General';
            $subjectPrompt = $set->subjectPrompts()->firstOrCreate(
                ['subject' => $subjectKey],
                [
                    'prompt' => sprintf('Generate questions for %s at %s difficulty.', $subjectKey, $this->difficulty),
                    'question_count' => 0,
                    'difficulty' => strtolower($this->difficulty),
                ]
            );

            foreach ($questions as $q) {

                $question = $set->questions()->create([
                    'question' => $q['question'] ?? 'Untitled',
                    'subject_prompt_id' => $subjectPrompt->id,
                    'type' => 'mcq',
                    'difficulty' => strtolower($this->difficulty),
                    'correct_answer' => $q['correctAnswer'] ?? null,
                ]);

                foreach ($q['answers'] ?? [] as $ans) {
                    $question->options()->create([
                        'option_text' => $ans,
                        'is_correct' => strtolower($ans) === strtolower($q['correctAnswer'] ?? ''),
                    ]);
                }
            }

            // Update counts and metadata
            $set->update([
                'total_questions' => $set->questions()->count(),
                'subjects' => $this->subjects,
                'title' => $this->formTitle(),
                'description' => $this->formDescription(),
            ]);

            $subjectPrompt->question_count = $subjectPrompt->questions()->count();
            $subjectPrompt->save();
        });

        $this->questions = array_merge($this->questions, $questions);
        $this->isLoading = false;

        $this->dispatch('question-set-updated', [
            'questionSetId' => $set->id,
            'title' => $set->title,
        ]);

        AiGeneration::create([
            'user_id' => Auth::id(),
            'question_set_id' => $set->id,
            'topic' => $this->subject,
            'subject' => $this->subject,
            'question_count' => count($questions),
            'difficulty' => $this->difficulty,
            'prompt' => "Generate {$this->count} questions",
            'response' => json_encode($questions),
        ]);
    }

    /* ---------------------------
        ERROR
    ----------------------------*/
    #[On('generationError')]
    public function handleError($error = 'Error')
    {
        $this->error = $error;
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.pages.userpages.question.generated-questions');
    }
}
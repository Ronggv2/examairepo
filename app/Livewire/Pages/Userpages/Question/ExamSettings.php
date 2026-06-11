<?php

namespace App\Livewire\Pages\Userpages\Question;

use App\Models\ExamSetting;
use App\Models\QuestionSet;
use Livewire\Attributes\On;
use Livewire\Component;

class ExamSettings extends Component
{
    public $subject = '';
    public $difficulty = 'Easy';
    public $questionCount = 10;
    public $autoChange = true;
    public $assignMethod = 'random';
    public $questionsPerUser = 10;
    public $repeatPolicy = 'none';

    public $questionSetId = null;
    public $isAddingToExisting = false;

    public $error = '';
    public $success = '';
    public $isGenerating = false;

    public function mount()
    {
        $this->questionSetId = request()->query('question_set');
        $this->isAddingToExisting = !empty($this->questionSetId);

        if ($this->questionSetId) {
            $this->loadExamSettings($this->questionSetId);
        }
    }

    protected function loadExamSettings($questionSetId): void
    {
        $settings = ExamSetting::where('question_set_id', $questionSetId)->first();

        if (!$settings) {
            return;
        }

        $this->autoChange = (bool) $settings->auto_change;
        $this->assignMethod = $settings->assign_method;
        $this->questionsPerUser = $settings->questions_per_user;
        $this->repeatPolicy = $settings->repeat_policy;
    }

    protected function ensureQuestionSet(): QuestionSet
    {
        if ($this->questionSetId) {
            return QuestionSet::findOrFail($this->questionSetId);
        }

        $set = QuestionSet::create([
            'user_id' => auth()->id(),
            'title' => sprintf('%s - %s', ucfirst(trim($this->subject) ?: 'General'), ucfirst($this->difficulty)),
            'subjects' => [$this->subject ?: 'General'],
            'difficulty' => strtolower($this->difficulty),
            'description' => sprintf('AI-generated questions for %s at %s difficulty.', $this->subject ?: 'General', $this->difficulty),
            'is_ai_generated' => true,
            'status' => 'draft',
            'total_questions' => 0,
        ]);

        $this->questionSetId = $set->id;
        return $set;
    }

    public function saveSettings(): void
    {
        $validated = $this->validate([
            'autoChange' => 'boolean',
            'assignMethod' => 'required|in:random,fixed,manual',
            'questionsPerUser' => 'required|integer|min:1',
            'repeatPolicy' => 'required|in:none,within_exam,across_attempts',
        ]);

        $set = $this->ensureQuestionSet();

        ExamSetting::updateOrCreate(
            ['question_set_id' => $set->id],
            [
                'auto_change' => $validated['autoChange'],
                'assign_method' => $validated['assignMethod'],
                'questions_per_user' => $validated['questionsPerUser'],
                'repeat_policy' => $validated['repeatPolicy'],
            ]
        );

        $this->dispatch('question-set-updated', [
            'questionSetId' => $set->id,
            'title' => $set->title,
        ]);

        $this->isAddingToExisting = true;
        $this->error = '';
        $this->success = 'Settings saved to database successfully.';
    }

    public function updated($property)
    {
        if (in_array($property, ['subject', 'difficulty'])) {
            $this->dispatch('form-input-updated', [
                'subject' => $this->subject,
                'difficulty' => $this->difficulty,
            ]);
        }
    }

    public function generateExam()
    {
        if (empty(trim($this->subject))) {
            $this->error = 'Subject required';
            return;
        }

        if ($this->isGenerating) return;

        $this->isGenerating = true;

        $this->dispatch('generate-exam',
            subject: $this->subject,
            difficulty: $this->difficulty,
            count: $this->questionCount,
            questionSetId: $this->questionSetId,
            isAddingToExisting: $this->isAddingToExisting,
        );
    }

    #[On('questionsGenerated')]
    public function done()
    {
        $this->isGenerating = false;
        $this->error = '';
    }

    #[On('generationError')]
    public function fail()
    {
        $this->isGenerating = false;
    }

    public function render()
    {
        return view('livewire.pages.userpages.question.exam-settings');
    }
}
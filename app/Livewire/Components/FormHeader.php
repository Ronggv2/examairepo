<?php

namespace App\Livewire\Components;

use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\QuestionSet;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class FormHeader extends Component
{
    public $questionSetId = null;
    public $title = 'Untitled';
    public $questionSet = null;
    public $publishSuccess = '';
    public $timerRunning = false;

    #[On('question-set-updated')]
    public function onQuestionSetUpdated($questionSetId = null, $title = null): void
    {
        if ($questionSetId) {
            $this->questionSetId = $questionSetId;
            $this->loadQuestionSet();
        }
        if ($title) {
            $this->title = $title;
        }
    }

    #[On('timer-status-changed')]
    public function onTimerStatusChanged($payload = []): void
    {
        if (!empty($payload['questionSetId']) && $payload['questionSetId'] !== $this->questionSetId) {
            return;
        }

        $this->timerRunning = !empty($payload['isRunning']);
    }

    public function mount(): void
    {
        $this->questionSetId = $this->questionSetId ?: request()->query('question_set');

        if ($this->questionSetId) {
            $this->loadQuestionSet();
        }
    }

    public function loadQuestionSet(): void
    {
        if (!$this->questionSetId || !Auth::check()) {
            return;
        }

        $this->questionSet = QuestionSet::where('id', $this->questionSetId)
            ->where('user_id', Auth::id())
            ->first();

        if ($this->questionSet) {
            $this->title = $this->questionSet->title ?? 'Untitled';
        }
    }

    public function updated($property): void
    {
        if ($property === 'title' && $this->questionSetId) {
            QuestionSet::where('id', $this->questionSetId)
                ->where('user_id', Auth::id())
                ->update([
                    'title' => trim($this->title) ?: 'Untitled',
                ]);
            
            // Also update the local questionSet property if it exists
            if ($this->questionSet) {
                $this->questionSet->refresh();
            }
        }
    }

    public function publishQuestionSet(): void
    {
        $this->questionSetId = $this->questionSetId ?: request()->query('question_set');

        if (!$this->questionSetId) {
            return;
        }

        $questionSet = QuestionSet::where('id', $this->questionSetId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$questionSet) {
            return;
        }

        $questionSet->update([
            'status' => 'published',
            'title' => trim($this->title) ?: 'Untitled',
        ]);

        $this->questionSet = $questionSet->refresh();
        $this->title = $this->questionSet->title ?? 'Untitled';

        $exam = Exam::where('question_set_id', (int) $this->questionSetId)->first();

        if (! $exam) {
            $exam = Exam::create([
                'question_set_id' => (int) $this->questionSetId,
                'title' => $this->questionSet->title ?? 'Untitled Exam',
                'description' => $this->questionSet->description,
                'duration' => 30,
                'passing_score' => 50,
                'shuffle_questions' => true,
                'shuffle_options' => true,
                'is_public' => true,
            ]);
        }

        $joinCode = ExamSession::generateJoinCode();
        $session = ExamSession::updateOrCreate(
            ['exam_id' => $exam->id],
            [
                'user_id' => Auth::id(),
                'duration_minutes' => $exam->duration,
                'remaining_seconds' => $exam->duration * 60,
                'is_paused' => false,
                'is_submitted' => false,
                'started_at' => now(),
                'last_activity_at' => now(),
                'ends_at' => now()->addMinutes($exam->duration),
                'join_code' => $joinCode,
            ]
        );

        $publishMessage = 'Published exam successfully. Join code: ' . $session->join_code;

        $this->publishSuccess = $publishMessage;

        $this->dispatch('question-set-published', [
            'questionSetId' => $this->questionSetId,
            'examSessionId' => $session->id,
            'joinCode' => $session->join_code,
            'joinLink' => route('joinexam', ['code' => $session->join_code]),
            'publishedMessage' => $publishMessage,
        ]);
    }

    public function unpublishQuestionSet(): void
    {
        $this->questionSetId = $this->questionSetId ?: request()->query('question_set');

        if (!$this->questionSetId) {
            return;
        }

        $questionSet = QuestionSet::where('id', $this->questionSetId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$questionSet) {
            return;
        }

        // Set back to draft
        $questionSet->update([
            'status' => 'draft',
        ]);

        // Remove associated exam and sessions if present
        $exam = Exam::where('question_set_id', (int) $this->questionSetId)->first();
        if ($exam) {
            ExamSession::where('exam_id', $exam->id)->delete();
            $exam->delete();
        }

        $this->questionSet = $questionSet->refresh();
        $this->publishSuccess = 'Unpublished exam successfully.';

        $this->dispatch('question-set-unpublished', [
            'questionSetId' => $this->questionSetId,
        ]);
    }

    public function render()
    {
        return view('livewire.components.form-header');
    }
}

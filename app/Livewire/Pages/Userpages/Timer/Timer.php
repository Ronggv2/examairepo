<?php

namespace App\Livewire\Pages\Userpages\Timer;

use App\Models\Exam;
use App\Models\ExamSession;
use Livewire\Attributes\On;
use Livewire\Component;

class Timer extends Component
{
    public $questionSetId = null;
    public $examSessionId = null;
    public $joinCode = '';
    public $joinLink = '';
    public $publishedMessage = '';
    public $durationMinutes = 30;
    public $remainingSeconds = 0;
    public $isPaused = false;
    public $isSubmitted = false;
    public $startedAt = null;
    public $endsAt = null;
    public $status = 'pending';

    public function mount()
    {
        $this->questionSetId = request()->query('question_set');
        $this->loadSession();
    }

    #[On('question-set-published')]
    public function onQuestionSetPublished($payload = [])
    {
        $this->questionSetId = $payload['questionSetId'] ?? $this->questionSetId;
        $this->examSessionId = $payload['examSessionId'] ?? null;
        $this->joinCode = $payload['joinCode'] ?? $this->joinCode;
        $this->joinLink = $payload['joinLink'] ?? $this->joinLink;
        $this->publishedMessage = $payload['publishedMessage'] ?? '';

        $this->loadSession();
    }

    public function loadSession(): void
    {
        if (!$this->questionSetId) {
            $this->status = 'no-session';
            $this->notifyTimerStatus();
            return;
        }

        $exam = Exam::where('question_set_id', $this->questionSetId)->first();

        if (!$exam) {
            $this->status = 'no-exam';
            $this->notifyTimerStatus();
            return;
        }

        if (!$this->examSessionId) {
            $session = ExamSession::where('exam_id', $exam->id)->latest('created_at')->first();
            $this->examSessionId = $session->id ?? null;
        } else {
            $session = ExamSession::fromCachedState($this->examSessionId) ?? ExamSession::find($this->examSessionId);
        }

        if (!$session) {
            $this->status = 'no-session';
            $this->durationMinutes = $exam->duration;
            $this->joinCode = '';
            $this->joinLink = '';
            $this->notifyTimerStatus();
            return;
        }

        $this->durationMinutes = $session->duration_minutes;
        $this->remainingSeconds = $session->remaining_seconds ?? ($session->duration_minutes * 60);
        $this->isPaused = $session->is_paused;
        $this->isSubmitted = $session->is_submitted;
        $this->startedAt = optional($session->started_at)->toDateTimeString();
        $this->endsAt = optional($session->ends_at)->toDateTimeString();
        $this->joinCode = $session->join_code;
        $this->joinLink = route('joinexam', ['code' => $session->join_code]);

        if ($session->is_submitted) {
            $this->status = 'ended';
        } elseif ($session->is_paused) {
            $this->status = 'paused';
        } elseif ($session->started_at) {
            $this->status = 'running';
        } else {
            $this->status = 'pending';
        }

        $this->notifyTimerStatus();
    }

    public function refreshTimer(): void
    {
        if (!$this->examSessionId) {
            $this->notifyTimerStatus();
            return;
        }

        $session = ExamSession::fromCachedState($this->examSessionId) ?? ExamSession::find($this->examSessionId);
        if (!$session) {
            $this->status = 'no-session';
            $this->notifyTimerStatus();
            return;
        }

        if ($session->is_submitted) {
            $this->status = 'ended';
            $this->remainingSeconds = 0;
            $this->notifyTimerStatus();
            return;
        }

        if ($session->is_paused) {
            $this->status = 'paused';
            $this->remainingSeconds = $session->remaining_seconds;
            $this->notifyTimerStatus();
            return;
        }

        if ($session->ends_at) {
            $seconds = max(0, (int) floor(now()->diffInSeconds($session->ends_at, false)));
            if ($seconds === 0) {
                $session->update([
                    'remaining_seconds' => 0,
                    'is_submitted' => true,
                    'is_paused' => false,
                    'ends_at' => now(),
                    'last_activity_at' => now(),
                ]);
                $session->cacheState();
                $this->status = 'ended';
                $this->remainingSeconds = 0;
                $this->notifyTimerStatus();
                return;
            }

            $this->remainingSeconds = $seconds;
            $this->status = 'running';
            $this->notifyTimerStatus();
        }
    }

    protected function notifyTimerStatus(): void
    {
        $this->dispatch('timer-status-changed', [
            'isRunning' => $this->status === 'running',
            'status' => $this->status,
            'questionSetId' => $this->questionSetId,
        ]);
    }

    public function startTimer(): void
    {
        if (!$this->questionSetId) {
            return;
        }

        $exam = Exam::where('question_set_id', $this->questionSetId)->first();
        if (!$exam) {
            return;
        }

        $session = ExamSession::firstOrNew(['exam_id' => $exam->id]);
        if (!$session->exists) {
            $session->join_code = ExamSession::generateJoinCode();
        }

        $duration = (int) $this->durationMinutes;

        $session->fill([
            'user_id' => auth()->id(),
            'duration_minutes' => $duration,
            'remaining_seconds' => $duration * 60,
            'is_paused' => false,
            'is_submitted' => false,
            'started_at' => now(),
            'last_activity_at' => now(),
            'ends_at' => now()->addMinutes($duration),
        ]);
        $session->save();
        $session->cacheState();

        $this->examSessionId = $session->id;
        $this->joinCode = $session->join_code;
        $this->joinLink = route('joinexam', ['code' => $session->join_code]);
        $this->status = 'running';
        $this->notifyTimerStatus();
        $this->refreshTimer();
    }

    public function pauseTimer(): void
    {
        if (!$this->examSessionId) {
            return;
        }

        $session = ExamSession::find($this->examSessionId);
        if (!$session || $session->is_paused || $session->is_submitted) {
            return;
        }

        $remaining = max(0, (int) floor(now()->diffInSeconds($session->ends_at, false)));
        $session->update([
            'remaining_seconds' => $remaining,
            'is_paused' => true,
            'ends_at' => null,
            'last_activity_at' => now(),
        ]);
        $session->refresh();
        $session->cacheState();

        $this->refreshTimer();
    }

    public function resumeTimer(): void
    {
        if (!$this->examSessionId) {
            return;
        }

        $session = ExamSession::find($this->examSessionId);
        if (!$session || !$session->is_paused || $session->is_submitted) {
            return;
        }

        $session->update([
            'is_paused' => false,
            'ends_at' => now()->addSeconds((int) ($session->remaining_seconds ?? ($session->duration_minutes * 60))),
            'last_activity_at' => now(),
        ]);
        $session->refresh();
        $session->cacheState();

        $this->refreshTimer();
    }

    public function endTimer(): void
    {
        if (!$this->examSessionId) {
            return;
        }

        $session = ExamSession::find($this->examSessionId);
        if (!$session || $session->is_submitted) {
            return;
        }

        $session->update([
            'remaining_seconds' => 0,
            'is_paused' => false,
            'is_submitted' => true,
            'ends_at' => now(),
            'last_activity_at' => now(),
        ]);
        $session->refresh();
        $session->cacheState();

        $this->refreshTimer();
    }

    public function updateDuration(): void
    {
        if (!$this->questionSetId) {
            return;
        }

        $duration = max(1, (int) $this->durationMinutes);

        $exam = Exam::where('question_set_id', $this->questionSetId)->first();
        if ($exam) {
            $exam->update([
                'duration' => $duration,
            ]);
        }

        if ($this->examSessionId) {
            $session = ExamSession::find($this->examSessionId);
            if ($session && !$session->is_submitted) {
                $session->update([
                    'duration_minutes' => $duration,
                    'remaining_seconds' => $duration * 60,
                    'ends_at' => $session->is_paused ? null : now()->addMinutes($duration),
                    'last_activity_at' => now(),
                ]);
                $session->refresh();
                $session->cacheState();
            }
        }

        $this->refreshTimer();
    }

    public function formatMilliseconds(): string
    {
        $seconds = $this->remainingSeconds;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%02d : %02d : %02d', $hours, $minutes, $seconds);
    }

    public function render()
    {
        return view('livewire.pages.userpages.timer.timer');
    }
}

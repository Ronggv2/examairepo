<?php

namespace App\Livewire\Pages\Auth\Exam;

use App\Models\ExamAttempt;
use Livewire\Component;

class Examresult extends Component
{
    public $attempt;
    public $score = 0;
    public $correct = 0;
    public $total = 0;
    public $timeUsed = '0 sec';
    public $timeLeft = '0 sec';
    public $examTime = '0 sec';

    public function mount()
    {
        $attemptId = request()->query('attempt_id');
        $this->attempt = ExamAttempt::with('answers.question')->find($attemptId);

        if (!$this->attempt) {
            return redirect('/');
        }

        $this->score = $this->attempt->score;
        $this->correct = $this->attempt->correct_count;
        $this->total = $this->attempt->answers->count();
        $this->timeUsed = $this->formatSeconds($this->attempt->time_used_seconds);
        $this->timeLeft = $this->formatSeconds($this->attempt->time_left_seconds);
        $this->examTime = $this->formatSeconds($this->attempt->exam_duration_seconds);
    }

    public function returnHome()
    {
        return redirect('/');
    }

    protected function formatSeconds(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function render()
    {
        return view('livewire.pages.auth.exam.examresult')
         ->layout('components.layouts.app', [
                'title' => 'Exam Result',
                'showLoader' => false,
            ]);
    }
}

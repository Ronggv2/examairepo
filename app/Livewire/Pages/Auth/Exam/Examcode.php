<?php

namespace App\Livewire\Pages\Auth\Exam;

use App\Models\ExamSession;
use Livewire\Component;

class Examcode extends Component
{
    public $exam_code = '';
    public $joinError = '';

    public function joinExam()
    {
        $code = strtoupper(trim($this->exam_code));

        if (!$code) {
            $this->joinError = 'Please enter a valid exam code.';
            return;
        }

        $session = ExamSession::where('join_code', $code)->first();

        if (!$session || $session->is_submitted) {
            $this->joinError = 'This exam code is invalid or the session has ended.';
            return;
        }

        return redirect()->route('joinexam', ['code' => $code]);
    }

    public function render()
    {
        return view('livewire.pages.auth.exam.examcode');
    }
}

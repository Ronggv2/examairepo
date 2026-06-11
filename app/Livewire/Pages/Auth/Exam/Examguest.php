<?php

namespace App\Livewire\Pages\Auth\Exam;

use App\Models\ExamSession;
use Livewire\Component;

class Examguest extends Component
{
    public $exam_code = '';
    public $guest_name = '';
    public $joinError = '';
    public $session;

    public function mount($exam_code = null)
    {
        $this->exam_code = strtoupper(trim($exam_code ?? ''));

        if ($this->exam_code) {
            $this->session = ExamSession::where('join_code', $this->exam_code)->first();

            if (!$this->session || $this->session->is_submitted) {
                $this->joinError = 'Invalid or expired exam code. Please enter a valid code first.';
            }
        }
    }

    public function joinExam()
    {
        if (!$this->guest_name) {
            $this->joinError = 'Please enter your name to join the exam.';
            return;
        }

        if (!$this->session) {
            $this->joinError = 'Unable to join exam. Please verify your exam code.';
            return;
        }

        if ($this->session->is_submitted) {
            $this->joinError = 'This exam session has ended and cannot be joined.';
            return;
        }

        session()->put('guest_name', trim($this->guest_name));
        session()->put('exam_session_id', $this->session->id);

        return redirect()->route('examform', ['code' => $this->exam_code]);
    }

    public function render()
    {
        return view('livewire.pages.auth.exam.examguest');
    }
}

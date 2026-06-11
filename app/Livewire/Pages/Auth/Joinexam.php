<?php

namespace App\Livewire\Pages\Auth;

use App\Models\ExamSession;
use Livewire\Component;

class Joinexam extends Component
{
    public $exam_code = '';
    public $joinError = '';

    public function mount()
    {
        $this->exam_code = strtoupper(trim(request()->query('code', '')));

        if ($this->exam_code) {
            $session = ExamSession::where('join_code', $this->exam_code)->first();

            if (!$session || $session->is_submitted) {
                $this->joinError = 'Invalid or expired exam code. Please enter a valid join code.';
                $this->exam_code = '';
            }
        }
    }

    public function render()
    {
        return view('livewire.pages.auth.joinexam')
          ->layout('components.layouts.auth', [
                'title' => 'Join Exam',
                'showLoader' => false,
            ]);
    }
}

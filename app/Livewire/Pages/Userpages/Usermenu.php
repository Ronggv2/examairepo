<?php

namespace App\Livewire\Pages\Userpages;

use Livewire\Component;

class Usermenu extends Component
{
    public $activeTab = 'question';
    public $subject = '';
    public $difficulty = 'Easy';
    public $questionCount = 10;
    public $autoChange = true;

    public function generateExam()
    {
        $this->dispatch('generate-exam', [
            'subject' => $this->subject,
            'difficulty' => $this->difficulty,
            'count' => $this->questionCount
        ]);
    }

    public function mount()
    {
        $this->activeTab = request()->query('tab', 'question');
    }
    
    public function render()
    {
        return view('livewire.pages.userpages.usermenu')
            ->layout('components.layouts.app', [
                'title' => 'Question ',
                'showLoader' => false,
            ]);
    }
}

<?php

namespace App\Livewire\Pages\Userpages;

use App\Models\QuestionSet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Userdashboard extends Component
{
    public $questionSets = [];

    public function mount()
    {
        if (!Auth::check()) {
            return;
        }

        $this->loadQuestionSets();
    }

    public function loadQuestionSets()
    {
        $this->questionSets = QuestionSet::with(['exams.session'])
            ->where('user_id', Auth::id())
            ->where('is_ai_generated', true)
            ->latest('updated_at')
            ->get()
            ->map(function (QuestionSet $qs) {
                $latestExam = $qs->exams->sortByDesc('id')->first();
                $session = $latestExam?->session;

                $timerRunning = false;
                if ($session && !$session->is_submitted && !$session->is_paused && $session->ends_at && now()->lt($session->ends_at)) {
                    $timerRunning = true;
                }

                return [
                    'id' => $qs->id,
                    'title' => $qs->title,
                    'description' => $qs->description,
                    'subjects' => $qs->subjects ?? [],
                    'subjects_string' => $qs->getSubjectsString(),
                    'total_questions' => $qs->total_questions,
                    'status' => $qs->status,
                    'timer_running' => $timerRunning,
                    'updated_at' => $qs->updated_at,
                ];
            })
            ->toArray();
    }

    public function deleteQuestionSet($questionSetId)
    {
        $questionSet = QuestionSet::find($questionSetId);

        if (!$questionSet || $questionSet->user_id !== Auth::id()) {
            session()->flash('error', 'Unauthorized or question set not found.');
            return;
        }

        // Delete all related questions
        $questionSet->questions()->delete();

        // Delete the question set
        $questionSet->delete();

        session()->flash('success', 'Question form deleted successfully.');
        
        // Redirect to refresh the page
        $this->redirect(route('user'));
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.pages.userpages.userdashboard')
          ->layout('components.layouts.app', [
                'title' => 'User Dashboard',
                'showLoader' => false,
            ]);
    }
}

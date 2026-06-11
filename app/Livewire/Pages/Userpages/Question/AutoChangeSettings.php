<?php

namespace App\Livewire\Pages\Userpages\Question;

use App\Models\ExamSetting;
use App\Models\QuestionSet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AutoChangeSettings extends Component
{
    public $questionSetId = null;
    public $assignMethod = 'random';
    public $questionsPerUser = 10;
    public $repeatPolicy = 'within_exam';
    public $error = '';
    public $success = '';

    public function mount()
    {
        $this->questionSetId = request()->query('question_set');

        if ($this->questionSetId) {
            $settings = ExamSetting::where('question_set_id', $this->questionSetId)
                ->first();

            if ($settings) {
                $this->assignMethod = $settings->assign_method;
                $this->questionsPerUser = $settings->questions_per_user;
                $this->repeatPolicy = $settings->repeat_policy;
            }
        }
    }

    public function saveSettings()
    {
        $validated = $this->validate([
            'assignMethod' => 'required|in:random,fixed,manual',
            'questionsPerUser' => 'required|integer|min:1',
            'repeatPolicy' => 'required|in:within_exam,across_attempts',
        ]);

        if (!$this->questionSetId) {
            $this->error = 'Unable to save settings: no active question set selected.';
            $this->success = '';
            return;
        }

        $set = QuestionSet::where('id', $this->questionSetId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$set) {
            $this->error = 'Question set not found or not accessible.';
            $this->success = '';
            return;
        }

        ExamSetting::updateOrCreate(
            ['question_set_id' => $set->id],
            [
                'auto_change' => true,
                'assign_method' => $validated['assignMethod'],
                'questions_per_user' => $validated['questionsPerUser'],
                'repeat_policy' => $validated['repeatPolicy'],
            ]
        );

        $this->error = '';
        $this->success = 'Auto-change settings saved for the current question set.';
    }

    public function render()
    {
        return view('livewire.pages.userpages.question.auto-change-settings');
    }
}

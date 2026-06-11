<?php

namespace App\Livewire\Pages\Auth\Exam;

use App\Models\ExamAttempt;
use App\Models\ExamSession;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Examform extends Component
{
    public $exam_code = '';
    public $guest_name = '';
    public $session;
    public $exam;
    public $questionSet;
    public $questions = [];
    public $selectedAnswers = [];
    public $remainingSeconds = 0;
    public $showError = false;
    public $errorMessage = '';
    public $examSetting;

    public function mount()
    {
        $this->exam_code = strtoupper(trim(request()->query('code', '')));

        if (!$this->exam_code) {
            return redirect()->route('joinexam');
        }

        $this->session = ExamSession::with(['exam.questionSet.user', 'exam.questionSet.examSetting'])
            ->where('join_code', $this->exam_code)
            ->first();

        if (!$this->session || $this->session->is_submitted) {
            return redirect()->route('joinexam', ['code' => $this->exam_code]);
        }

        $this->guest_name = session('guest_name', 'Guest');
        if (!$this->guest_name) {
            return redirect()->route('joinexam', ['code' => $this->exam_code]);
        }

        $this->exam = $this->session->exam;
        $this->questionSet = $this->exam?->questionSet;
        $this->questionSet?->loadMissing('user');
        $this->examSetting = $this->questionSet?->examSetting;
        $this->questions = $this->loadQuestionsForExam();
        $this->remainingSeconds = $this->computeRemainingSeconds();
    }

    public function tick()
    {
        if (!$this->session || $this->session->is_submitted) {
            $this->remainingSeconds = 0;
            return;
        }

        $cachedState = ExamSession::getCachedState($this->session->id);
        if ($cachedState) {
            $this->session->fill([
                'remaining_seconds' => $cachedState['remaining_seconds'],
                'is_paused' => $cachedState['is_paused'],
                'is_submitted' => $cachedState['is_submitted'],
                'ends_at' => $cachedState['ends_at'],
            ]);
        } else {
            $this->session->refresh();
        }

        $this->remainingSeconds = $this->computeRemainingSeconds();

        if ($this->remainingSeconds <= 0 && !$this->session->is_submitted) {
            $this->remainingSeconds = 0;
            return $this->submitExam(true);
        }
    }

    public function submitExam(bool $isAutoSubmit = false)
    {
        if (!$this->session || $this->session->is_submitted) {
            return;
        }

        $this->session->refresh();
        if ($this->session->is_submitted) {
            return;
        }

        $durationSeconds = $this->session->duration_minutes * 60;
        $timeUsedSeconds = max(0, $durationSeconds - $this->remainingSeconds);
        $timeLeftSeconds = max(0, $this->remainingSeconds);

        $totalMarks = 0;
        $score = 0;
        $correctCount = 0;
        $incorrectCount = 0;
        $unansweredCount = 0;
        $answersData = [];

        foreach ($this->questions as $question) {
            $selectedOptionId = $this->selectedAnswers[$question->id] ?? null;
            $selectedOption = $selectedOptionId ? $question->options->firstWhere('id', $selectedOptionId) : null;
            $answerText = $selectedOption?->option_text;
            $isCorrect = $selectedOption?->is_correct ?? false;
            $marks = (int) ($question->marks ?? 1);
            $marksAwarded = $isCorrect ? $marks : 0;

            $totalMarks += $marks;
            $score += $marksAwarded;

            if ($selectedOptionId === null) {
                $unansweredCount++;
            } elseif ($isCorrect) {
                $correctCount++;
            } else {
                $incorrectCount++;
            }

            $answersData[] = [
                'question_id' => $question->id,
                'answer' => $answerText,
                'is_correct' => $isCorrect,
                'marks_awarded' => $marksAwarded,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $percentage = $totalMarks > 0 ? round(($score / $totalMarks) * 100, 2) : 0;

        DB::transaction(function () use (&$attempt, $durationSeconds, $timeUsedSeconds, $timeLeftSeconds, $totalMarks, $score, $correctCount, $incorrectCount, $unansweredCount, $percentage, $answersData, $isAutoSubmit) {
            $attempt = ExamAttempt::create([
                'exam_id' => $this->exam->id,
                'user_id' => $this->session->user_id,
                'guest_name' => $this->guest_name,
                'guest_email' => null,
                'score' => $score,
                'total_marks' => $totalMarks,
                'correct_count' => $correctCount,
                'incorrect_count' => $incorrectCount,
                'unanswered_count' => $unansweredCount,
                'percentage' => $percentage,
                'exam_duration_seconds' => $durationSeconds,
                'time_used_seconds' => $timeUsedSeconds,
                'time_left_seconds' => $timeLeftSeconds,
                'started_at' => $this->session->started_at,
                'submitted_at' => now(),
            ]);

            $attempt->answers()->createMany($answersData);

            if ($isAutoSubmit) {
                $this->session->update([
                    'remaining_seconds' => 0,
                    'is_submitted' => true,
                    'is_paused' => false,
                    'ends_at' => now(),
                ]);
                $this->session->refresh();
                $this->session->cacheState();
            }
        });

        return redirect()->route('examresult', ['attempt_id' => $attempt->id]);
    }

    protected function computeRemainingSeconds(): int
    {
        if ($this->session?->is_submitted) {
            return 0;
        }

        if ($this->session?->ends_at) {
            $seconds = now()->diffInSeconds($this->session->ends_at, false);
            return max(0, $seconds);
        }

        return $this->session->remaining_seconds ?? ($this->session->duration_minutes * 60);
    }

    protected function loadQuestionsForExam()
    {
        if (!$this->questionSet) {
            return collect();
        }

        $query = $this->questionSet->questions()->with('options');

        if ($this->examSetting && $this->examSetting->auto_change) {
            $questionsPerUser = max(1, $this->examSetting->questions_per_user);

            if ($this->examSetting->assign_method === 'random') {
                return $query->inRandomOrder()->take($questionsPerUser)->get();
            }

            if ($this->examSetting->assign_method === 'fixed') {
                return $query->orderBy('id')->take($questionsPerUser)->get();
            }
        }

        return $query->get();
    }

    public function formatTimer(): string
    {
        $seconds = $this->remainingSeconds;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function render()
    {
        return view('livewire.pages.auth.exam.examform')
            ->layout('components.layouts.app', [
                'title' => 'Exam',
                'showLoader' => false,
            ]);
    }
}

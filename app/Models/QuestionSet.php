<?php

namespace App\Models;

use App\Models\ExamSetting;
use Illuminate\Database\Eloquent\Model;

class QuestionSet extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'subjects',
        'difficulty',
        'status',
        'is_ai_generated',
        'total_questions',
    ];

    protected $casts = [
        'subjects' => 'array',
        'is_ai_generated' => 'boolean',
    ];

    public function getSubjectsString()
    {
        if (is_array($this->subjects)) {
            return implode(', ', $this->subjects);
        }

        return $this->subjects ? (string) $this->subjects : '';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function subjectPrompts()
    {
        return $this->hasMany(SubjectPrompt::class);
    }

    public function examSetting()
    {
        return $this->hasOne(ExamSetting::class);
    }
}
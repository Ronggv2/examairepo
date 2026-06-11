<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_set_id',
        'subject_prompt_id',
        'question',
        'type',
        'difficulty',
        'correct_answer',
        'explanation',
        'marks',
    ];

    public function questionSet()
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
    public function subjectPrompt()
    {
        return $this->belongsTo(SubjectPrompt::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
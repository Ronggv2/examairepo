<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'question_set_id',
        'title',
        'description',
        'duration',
        'passing_score',
        'shuffle_questions',
        'shuffle_options',
        'is_public',
    ];

    public function questionSet()
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public function session()
    {
        return $this->hasOne(ExamSession::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
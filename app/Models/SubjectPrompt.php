<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectPrompt extends Model
{
    protected $fillable = [
        'question_set_id',
        'subject',
        'prompt',
        'question_count',
    ];

    public function questionSet()
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}

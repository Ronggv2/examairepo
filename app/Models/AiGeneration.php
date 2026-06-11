<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiGeneration extends Model
{
    protected $fillable = [
        'user_id',
        'question_set_id',
        'topic',
        'subject',
        'question_count',
        'difficulty',
        'prompt',
        'response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questionSet()
    {
        return $this->belongsTo(QuestionSet::class);
    }
}
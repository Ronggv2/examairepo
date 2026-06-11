<?php

namespace App\Models;

use App\Models\QuestionSet;
use Illuminate\Database\Eloquent\Model;

class ExamSetting extends Model
{
    protected $fillable = [
        'question_set_id',
        'auto_change',
        'assign_method',
        'questions_per_user',
        'repeat_policy',
    ];

    protected $casts = [
        'auto_change' => 'boolean',
        'questions_per_user' => 'integer',
    ];

    public function questionSet()
    {
        return $this->belongsTo(QuestionSet::class);
    }
}

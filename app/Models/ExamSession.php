<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExamSession extends Model
{
    protected $fillable = [
        'exam_id',
        'user_id',
        'duration_minutes',
        'remaining_seconds',
        'is_paused',
        'is_submitted',
        'started_at',
        'last_activity_at',
        'ends_at',
        'join_code',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'remaining_seconds' => 'integer',
        'is_paused' => 'boolean',
        'is_submitted' => 'boolean',
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateJoinCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (self::where('join_code', $code)->exists());

        return $code;
    }
}

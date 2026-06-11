<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
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

    public function cacheKey(): string
    {
        return "exam_session:{$this->id}:state";
    }

    public static function cacheKeyFor(int $id): string
    {
        return "exam_session:{$id}:state";
    }

    public function toCacheState(): array
    {
        return [
            'id' => $this->id,
            'exam_id' => $this->exam_id,
            'user_id' => $this->user_id,
            'duration_minutes' => $this->duration_minutes,
            'remaining_seconds' => $this->remaining_seconds,
            'is_paused' => $this->is_paused,
            'is_submitted' => $this->is_submitted,
            'started_at' => $this->started_at?->toDateTimeString(),
            'last_activity_at' => $this->last_activity_at?->toDateTimeString(),
            'ends_at' => $this->ends_at?->toDateTimeString(),
            'join_code' => $this->join_code,
        ];
    }

    public function cacheState(): void
    {
        Cache::put($this->cacheKey(), $this->toCacheState(), now()->addMinutes(30));
    }

    public static function getCachedState(int $id): ?array
    {
        return Cache::get(self::cacheKeyFor($id));
    }

    public static function fromCachedState(int $id): ?self
    {
        $state = self::getCachedState($id);

        if (! $state) {
            return null;
        }

        $session = new self();
        $session->exists = true;
        $session->setRawAttributes($state, true);

        return $session;
    }

    public static function forgetCachedState(int $id): void
    {
        Cache::forget(self::cacheKeyFor($id));
    }

    public function setRemainingSecondsAttribute($value)
    {
        $this->attributes['remaining_seconds'] = is_null($value)
            ? null
            : (int) floor($value);
    }
}

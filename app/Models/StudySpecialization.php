<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudySpecialization extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description', 'color', 'icon'];

    protected $appends = ['dot'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(StudySession::class);
    }

    public function flashcards(): HasMany
    {
        return $this->hasMany(StudyFlashcard::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(StudyNote::class)->orderBy('order')->orderBy('created_at', 'desc');
    }

    public function totalHours(): float
    {
        return round($this->sessions()->sum('duration_minutes') / 60, 1);
    }

    public function todayMinutes(): int
    {
        return (int) $this->sessions()
            ->whereDate('started_at', today())
            ->sum('duration_minutes');
    }

    public function getDotAttribute(): string
    {
        return \App\Models\Category::COLORS[$this->color]['dot'] ?? 'bg-slate-400';
    }

    public function getBadgeAttribute(): string
    {
        return \App\Models\Category::COLORS[$this->color]['badge'] ?? 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-slate-400';
    }
}

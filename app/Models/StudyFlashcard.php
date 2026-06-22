<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class StudyFlashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_specialization_id', 'user_id',
        'front', 'back', 'difficulty',
        'reviewed_at', 'next_review_at', 'review_count', 'interval_days',
    ];

    protected function casts(): array
    {
        return [
            'difficulty' => 'integer',
            'review_count' => 'integer',
            'interval_days' => 'integer',
            'reviewed_at' => 'datetime',
            'next_review_at' => 'datetime',
        ];
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(StudySpecialization::class, 'study_specialization_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDueForReview(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('next_review_at')
              ->orWhere('next_review_at', '<=', now());
        });
    }

    public function scopeBySpecialization(Builder $query, $specializationId): Builder
    {
        return $query->where('study_specialization_id', $specializationId);
    }

    public function isDueForReview(): bool
    {
        return is_null($this->next_review_at) || $this->next_review_at->isPast();
    }

    public function review(int $difficulty): void
    {
        $this->difficulty = max(1, min(5, $difficulty));
        $this->review_count++;
        $this->reviewed_at = now();

        // Intervalo progressivo baseado na dificuldade
        $multiplier = match (true) {
            $difficulty <= 2 => 1,     // difícil: repete logo
            $difficulty === 3 => 2,    // médio
            $difficulty === 4 => 4,    // fácil
            $difficulty >= 5 => 7,     // muito fácil
        };
        $this->interval_days = max(1, ($this->interval_days ?: 1) * $multiplier);
        $this->next_review_at = now()->addDays($this->interval_days);

        $this->save();
    }
}

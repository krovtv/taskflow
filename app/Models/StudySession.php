<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_specialization_id', 'user_id',
        'started_at', 'ended_at', 'duration_minutes', 'notes',
    ];

    protected $appends = ['duration_formatted'];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
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

    public function getDurationFormattedAttribute(): string
    {
        $h = intdiv($this->duration_minutes ?? 0, 60);
        $m = ($this->duration_minutes ?? 0) % 60;
        return $h > 0 ? "{$h}h{$m}m" : "{$m}min";
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectPhase extends Model
{
    use HasFactory;

    public const STATUS_PENDENTE = 'pendente';
    public const STATUS_ANDAMENTO = 'em_andamento';
    public const STATUS_CONCLUIDO = 'concluido';

    public const STATUSES = [
        self::STATUS_PENDENTE => 'Pendente',
        self::STATUS_ANDAMENTO => 'Em andamento',
        self::STATUS_CONCLUIDO => 'Concluído',
    ];

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'order',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}

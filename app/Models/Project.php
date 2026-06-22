<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    public const STATUS_PLANEJAMENTO = 'planejamento';
    public const STATUS_ANDAMENTO = 'em_andamento';
    public const STATUS_CONCLUIDO = 'concluido';
    public const STATUS_CANCELADO = 'cancelado';

    public const STATUSES = [
        self::STATUS_PLANEJAMENTO => 'Planejamento',
        self::STATUS_ANDAMENTO => 'Em andamento',
        self::STATUS_CONCLUIDO => 'Concluído',
        self::STATUS_CANCELADO => 'Cancelado',
    ];

    public const STATUS_CLASSES = [
        self::STATUS_PLANEJAMENTO => 'bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 border-sky-200 dark:border-sky-800/50',
        self::STATUS_ANDAMENTO => 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/50',
        self::STATUS_CONCLUIDO => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50',
        self::STATUS_CANCELADO => 'bg-red-50 dark:bg-red-900/30 text-red-500 dark:text-red-400 border-red-200 dark:border-red-800/50',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(ProjectPhase::class)->orderBy('order');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusClassAttribute(): string
    {
        return self::STATUS_CLASSES[$this->status] ?? self::STATUS_CLASSES[self::STATUS_PLANEJAMENTO];
    }

    public function getProgressAttribute(): int
    {
        $total = $this->phases()->count();
        if ($total === 0) {
            $taskTotal = $this->tasks()->count();
            if ($taskTotal === 0) return 0;
            return (int) round(($this->tasks()->where('status', Task::STATUS_CONCLUIDO)->count() / $taskTotal) * 100);
        }
        $concluidas = $this->phases()->where('status', 'concluido')->count();
        return (int) round(($concluidas / $total) * 100);
    }
}

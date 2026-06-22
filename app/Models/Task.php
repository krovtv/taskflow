<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Builder;
    use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    // Status disponíveis
    public const STATUS_PENDENTE = 'pendente';
    public const STATUS_ANDAMENTO = 'em_andamento';
    public const STATUS_CONCLUIDO = 'concluido';

    public const STATUSES = [
        self::STATUS_PENDENTE => 'Pendente',
        self::STATUS_ANDAMENTO => 'Em andamento',
        self::STATUS_CONCLUIDO => 'Concluído',
    ];

    // Prioridades disponíveis
    public const PRIORITY_BAIXA = 'baixa';
    public const PRIORITY_MEDIA = 'media';
    public const PRIORITY_ALTA = 'alta';
    public const PRIORITY_URGENTE = 'urgente';

    public const PRIORITIES = [
        self::PRIORITY_BAIXA => 'Baixa',
        self::PRIORITY_MEDIA => 'Média',
        self::PRIORITY_ALTA => 'Alta',
        self::PRIORITY_URGENTE => 'Urgente',
    ];

    public const PRIORITY_CLASSES = [
        self::PRIORITY_BAIXA => 'bg-slate-100 text-slate-600 border-slate-200',
        self::PRIORITY_MEDIA => 'bg-blue-50 text-blue-600 border-blue-200',
        self::PRIORITY_ALTA => 'bg-amber-50 text-amber-600 border-amber-200',
        self::PRIORITY_URGENTE => 'bg-red-50 text-red-600 border-red-200',
    ];

    public const FREQUENCIES = [
        'daily' => 'Diária',
        'weekly' => 'Semanal',
        'monthly' => 'Mensal',
        'yearly' => 'Anual',
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'project_id',
        'project_phase_id',
        'title',
        'description',
        'category',
        'due_date',
        'status',
        'priority',
        'estimated_hours',
        'progress',
        'tags',
        'notified_at',
        'recurring_frequency',
        'recurring_end_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'notified_at' => 'datetime',
            'estimated_hours' => 'decimal:1',
            'progress' => 'integer',
            'recurring_end_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cat(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function phase(): BelongsTo
    {
        return $this->belongsTo(ProjectPhase::class, 'project_phase_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    /**
     * Escopo: tarefas que vencem em um intervalo de horas a partir de agora,
     * e que ainda não foram notificadas.
     */
    public function scopeDueSoonAndNotNotified(Builder $query, int $hours = 24): Builder
    {
        return $query->whereNull('notified_at')
            ->where('status', '!=', self::STATUS_CONCLUIDO)
            ->whereBetween('due_date', [now(), now()->addHours($hours)]);
    }

    /**
     * Escopo: tarefas atrasadas (due_date no passado e não concluídas).
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('due_date', '<', now())
            ->where('status', '!=', self::STATUS_CONCLUIDO);
    }

    public function getCategoryLabelAttribute(): string
    {
        return $this->cat?->name ?? ucfirst($this->category ?? '');
    }

    public function getCategoryBadgeAttribute(): string
    {
        $color = $this->cat?->color;
        return Category::COLORS[$color]['badge'] ?? 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-slate-400';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public function getPriorityClassAttribute(): string
    {
        return self::PRIORITY_CLASSES[$this->priority] ?? self::PRIORITY_CLASSES[self::PRIORITY_MEDIA];
    }

    public function getTagsArrayAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }
        return array_map('trim', explode(',', $this->tags));
    }

    public function isOverdue(): bool
    {
        return $this->due_date instanceof Carbon
            && $this->due_date->isPast()
            && $this->status !== self::STATUS_CONCLUIDO;
    }

    public function isRecurring(): bool
    {
        return !empty($this->recurring_frequency);
    }

    public function getNextRecurringDueDate(): ?Carbon
    {
        if (!$this->isRecurring() || !$this->due_date) {
            return null;
        }

        $next = $this->due_date->copy();

        if ($next->isFuture()) {
            return $next;
        }

        while ($next->isPast()) {
            $next = match ($this->recurring_frequency) {
                'daily' => $next->addDay(),
                'weekly' => $next->addWeek(),
                'monthly' => $next->addMonth(),
                'yearly' => $next->addYear(),
                default => $next,
            };

            if ($this->recurring_end_date && $next->isAfter($this->recurring_end_date)) {
                return null;
            }
        }

        return $next;
    }

    public function scopeRecurringDue(Builder $query): Builder
    {
        return $query->whereNotNull('recurring_frequency')
            ->where('status', '!=', self::STATUS_CONCLUIDO)
            ->where('due_date', '<=', now());
    }
}

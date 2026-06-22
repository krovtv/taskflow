<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'color'];

    public const COLORS = [
        'blue' => ['dot' => 'bg-blue-400', 'badge' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400', 'border' => 'border-blue-200 dark:border-blue-800/50', 'stroke' => '#60a5fa'],
        'amber' => ['dot' => 'bg-amber-400', 'badge' => 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400', 'border' => 'border-amber-200 dark:border-amber-800/50', 'stroke' => '#fbbf24'],
        'purple' => ['dot' => 'bg-purple-400', 'badge' => 'bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400', 'border' => 'border-purple-200 dark:border-purple-800/50', 'stroke' => '#c084fc'],
        'emerald' => ['dot' => 'bg-emerald-400', 'badge' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-800/50', 'stroke' => '#34d399'],
        'red' => ['dot' => 'bg-red-400', 'badge' => 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400', 'border' => 'border-red-200 dark:border-red-800/50', 'stroke' => '#f87171'],
        'pink' => ['dot' => 'bg-pink-400', 'badge' => 'bg-pink-50 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400', 'border' => 'border-pink-200 dark:border-pink-800/50', 'stroke' => '#f472b6'],
        'indigo' => ['dot' => 'bg-indigo-400', 'badge' => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400', 'border' => 'border-indigo-200 dark:border-indigo-800/50', 'stroke' => '#818cf8'],
        'rose' => ['dot' => 'bg-rose-400', 'badge' => 'bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400', 'border' => 'border-rose-200 dark:border-rose-800/50', 'stroke' => '#fb7185'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}

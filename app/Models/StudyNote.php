<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_specialization_id', 'user_id',
        'title', 'content', 'type', 'url', 'order',
    ];

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(StudySpecialization::class, 'study_specialization_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

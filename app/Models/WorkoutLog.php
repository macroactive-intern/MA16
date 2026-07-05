<?php

namespace App\Models;

use Database\Factories\WorkoutLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkoutLog extends Model
{
    /** @use HasFactory<WorkoutLogFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'coach_id',
        'completed_at',
        'program_name',
        'notes',
        'duration_minutes',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'date',
            'duration_minutes' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}

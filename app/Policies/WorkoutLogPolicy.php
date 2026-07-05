<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkoutLog;

class WorkoutLogPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['client', 'coach'], true);
    }

    public function view(User $user, WorkoutLog $workoutLog): bool
    {
        if ($user->role === 'client') {
            return $workoutLog->user_id === $user->id;
        }

        if ($user->role === 'coach') {
            return $workoutLog->coach_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    public function update(User $user, WorkoutLog $workoutLog): bool
    {
        return $user->role === 'client'
            && $workoutLog->user_id === $user->id
            && $workoutLog->completed_at->greaterThanOrEqualTo(now()->subDays(7)->startOfDay());
    }

    public function delete(User $user, WorkoutLog $workoutLog): bool
    {
        return $user->role === 'client'
            && $workoutLog->user_id === $user->id;
    }

    public function viewStats(User $user): bool
    {
        return $user->role === 'client';
    }
}

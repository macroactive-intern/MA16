<?php

namespace App\Http\Controllers;

use App\Models\WorkoutLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WorkoutLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', WorkoutLog::class);

        $user = $request->user();

        $logs = WorkoutLog::query()
            ->when($user->role === 'client', fn ($q) => $q->where('user_id', $user->id))
            ->when($user->role === 'coach',  fn ($q) => $q->where('coach_id', $user->id))
            ->latest('completed_at')
            ->get();

        return response()->json($logs);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', WorkoutLog::class);

        $validated = $request->validate([
            'completed_at'     => ['required', 'date', 'before_or_equal:today'],
            'program_name'     => ['nullable', 'string', 'max:100'],
            'notes'            => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:65535'],
        ]);

        $user = $request->user();

        $log = WorkoutLog::create([
            ...$validated,
            'user_id'  => $user->id,
            'coach_id' => $user->coach_id,
        ]);

        return response()->json($log, 201);
    }

    public function show(WorkoutLog $workoutLog): JsonResponse
    {
        $this->authorize('view', $workoutLog);

        return response()->json($workoutLog);
    }

    public function update(Request $request, WorkoutLog $workoutLog): JsonResponse
    {
        $this->authorize('update', $workoutLog);

        $validated = $request->validate([
            'program_name'     => ['sometimes', 'nullable', 'string', 'max:100'],
            'notes'            => ['sometimes', 'nullable', 'string'],
            'duration_minutes' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:65535'],
        ]);

        $workoutLog->update($validated);

        return response()->json($workoutLog->fresh());
    }

    public function destroy(WorkoutLog $workoutLog): Response
    {
        $this->authorize('delete', $workoutLog);

        $workoutLog->delete();

        return response()->noContent();
    }
}

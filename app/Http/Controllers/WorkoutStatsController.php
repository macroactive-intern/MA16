<?php

namespace App\Http\Controllers;

use App\Models\WorkoutLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkoutStatsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $this->authorize('viewStats', WorkoutLog::class);

        $user = $request->user();

        $logs = WorkoutLog::where('user_id', $user->id);

        $totalLogs = (clone $logs)->count();
        $totalDuration = (clone $logs)->sum('duration_minutes');

        $mostLoggedProgram = (clone $logs)
            ->whereNotNull('program_name')
            ->selectRaw('program_name, count(*) as count')
            ->groupBy('program_name')
            ->orderByDesc('count')
            ->orderBy('program_name')
            ->value('program_name');

        return response()->json([
            'total_logs'           => $totalLogs,
            'total_duration'       => (int) $totalDuration,
            'most_logged_program'  => $mostLoggedProgram,
        ]);
    }
}

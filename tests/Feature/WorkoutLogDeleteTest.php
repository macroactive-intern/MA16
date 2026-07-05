<?php

use App\Models\User;
use App\Models\WorkoutLog;

it('client can soft-delete their own log', function () {
    $client = User::factory()->client()->create();
    $log = WorkoutLog::factory()->create(['user_id' => $client->id]);

    $this->actingAs($client)
        ->deleteJson("/api/workout-logs/{$log->id}")
        ->assertNoContent();
});

it('client cannot delete another clients log', function () {
    $client = User::factory()->client()->create();
    $other = User::factory()->client()->create();
    $log = WorkoutLog::factory()->create(['user_id' => $other->id]);

    $this->actingAs($client)
        ->deleteJson("/api/workout-logs/{$log->id}")
        ->assertForbidden();
});

it('coach cannot delete a client log', function () {
    $coach = User::factory()->coach()->create();
    $client = User::factory()->client($coach)->create();
    $log = WorkoutLog::factory()->create(['user_id' => $client->id, 'coach_id' => $coach->id]);

    $this->actingAs($coach)
        ->deleteJson("/api/workout-logs/{$log->id}")
        ->assertForbidden();
});

it('row remains in the database after soft delete', function () {
    $client = User::factory()->client()->create();
    $log = WorkoutLog::factory()->create(['user_id' => $client->id]);

    $this->actingAs($client)->deleteJson("/api/workout-logs/{$log->id}");

    $this->assertDatabaseHas('workout_logs', ['id' => $log->id]);
});

it('deleted_at is set after soft delete', function () {
    $client = User::factory()->client()->create();
    $log = WorkoutLog::factory()->create(['user_id' => $client->id]);

    $this->actingAs($client)->deleteJson("/api/workout-logs/{$log->id}");

    expect($log->fresh()->deleted_at)->not->toBeNull();
});

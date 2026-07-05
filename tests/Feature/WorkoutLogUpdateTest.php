<?php

use App\Models\User;
use App\Models\WorkoutLog;

it('client can update their own log from 3 days ago', function () {
    $client = User::factory()->client()->create();
    $log = WorkoutLog::factory()->create([
        'user_id' => $client->id,
        'completed_at' => now()->subDays(3)->toDateString(),
    ]);

    $this->actingAs($client)
        ->putJson("/api/workout-logs/{$log->id}", ['notes' => 'Updated'])
        ->assertOk();
});

it('client cannot update their own log from 8 days ago', function () {
    $client = User::factory()->client()->create();
    $log = WorkoutLog::factory()->create([
        'user_id' => $client->id,
        'completed_at' => now()->subDays(8)->toDateString(),
    ]);

    $this->actingAs($client)
        ->putJson("/api/workout-logs/{$log->id}", ['notes' => 'Updated'])
        ->assertForbidden();
});

it('client cannot update another clients log', function () {
    $client = User::factory()->client()->create();
    $other = User::factory()->client()->create();
    $log = WorkoutLog::factory()->create([
        'user_id' => $other->id,
        'completed_at' => now()->toDateString(),
    ]);

    $this->actingAs($client)
        ->putJson("/api/workout-logs/{$log->id}", ['notes' => 'Updated'])
        ->assertForbidden();
});

it('coach cannot update a client log', function () {
    $coach = User::factory()->coach()->create();
    $client = User::factory()->client($coach)->create();
    $log = WorkoutLog::factory()->create([
        'user_id' => $client->id,
        'coach_id' => $coach->id,
        'completed_at' => now()->toDateString(),
    ]);

    $this->actingAs($coach)
        ->putJson("/api/workout-logs/{$log->id}", ['notes' => 'Updated'])
        ->assertForbidden();
});

it('client can update a log from exactly 7 days ago', function () {
    $client = User::factory()->client()->create();
    $log = WorkoutLog::factory()->create([
        'user_id' => $client->id,
        'completed_at' => now()->subDays(7)->toDateString(),
    ]);

    $this->actingAs($client)
        ->putJson("/api/workout-logs/{$log->id}", ['notes' => 'Updated'])
        ->assertOk();
});

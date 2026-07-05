<?php

use App\Models\User;
use App\Models\WorkoutLog;

it('client gets total log count', function () {
    $client = User::factory()->client()->create();
    WorkoutLog::factory()->count(3)->create(['user_id' => $client->id]);

    $this->actingAs($client)
        ->getJson('/api/workout-stats')
        ->assertOk()
        ->assertJsonFragment(['total_logs' => 3]);
});

it('client gets total duration', function () {
    $client = User::factory()->client()->create();
    WorkoutLog::factory()->create(['user_id' => $client->id, 'duration_minutes' => 60]);
    WorkoutLog::factory()->create(['user_id' => $client->id, 'duration_minutes' => 45]);
    WorkoutLog::factory()->create(['user_id' => $client->id, 'duration_minutes' => null]);

    $this->actingAs($client)
        ->getJson('/api/workout-stats')
        ->assertOk()
        ->assertJsonFragment(['total_duration' => 105]);
});

it('client gets most logged program', function () {
    $client = User::factory()->client()->create();
    WorkoutLog::factory()->create(['user_id' => $client->id, 'program_name' => 'Strength']);
    WorkoutLog::factory()->create(['user_id' => $client->id, 'program_name' => 'Strength']);
    WorkoutLog::factory()->create(['user_id' => $client->id, 'program_name' => 'Mobility']);

    $this->actingAs($client)
        ->getJson('/api/workout-stats')
        ->assertOk()
        ->assertJsonFragment(['most_logged_program' => 'Strength']);
});

it('soft-deleted logs are excluded from stats', function () {
    $client = User::factory()->client()->create();
    WorkoutLog::factory()->create(['user_id' => $client->id, 'duration_minutes' => 60]);
    $deleted = WorkoutLog::factory()->create(['user_id' => $client->id, 'duration_minutes' => 60]);
    $deleted->delete();

    $this->actingAs($client)
        ->getJson('/api/workout-stats')
        ->assertOk()
        ->assertJsonFragment(['total_logs' => 1, 'total_duration' => 60]);
});

it('other clients logs are excluded from stats', function () {
    $client = User::factory()->client()->create();
    $other = User::factory()->client()->create();
    WorkoutLog::factory()->create(['user_id' => $client->id, 'duration_minutes' => 30]);
    WorkoutLog::factory()->create(['user_id' => $other->id, 'duration_minutes' => 90]);

    $this->actingAs($client)
        ->getJson('/api/workout-stats')
        ->assertOk()
        ->assertJsonFragment(['total_logs' => 1, 'total_duration' => 30]);
});

it('coach cannot access workout stats', function () {
    $coach = User::factory()->coach()->create();

    $this->actingAs($coach)
        ->getJson('/api/workout-stats')
        ->assertForbidden();
});

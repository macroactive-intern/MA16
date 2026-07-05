<?php

use App\Models\User;

it('client can create a workout log', function () {
    $client = User::factory()->client()->create();

    $this->actingAs($client)
        ->postJson('/api/workout-logs', [
            'completed_at' => now()->toDateString(),
            'program_name' => 'Strength',
            'notes' => 'Good session',
            'duration_minutes' => 60,
        ])
        ->assertCreated();
});

it('coach cannot create a workout log', function () {
    $coach = User::factory()->coach()->create();

    $this->actingAs($coach)
        ->postJson('/api/workout-logs', [
            'completed_at' => now()->toDateString(),
        ])
        ->assertForbidden();
});

it('user_id is taken from the authenticated client', function () {
    $client = User::factory()->client()->create();

    $this->actingAs($client)->postJson('/api/workout-logs', [
        'completed_at' => now()->toDateString(),
    ]);

    $this->assertDatabaseHas('workout_logs', ['user_id' => $client->id]);
});

it('coach_id is copied from the clients assigned coach', function () {
    $coach = User::factory()->coach()->create();
    $client = User::factory()->client($coach)->create();

    $this->actingAs($client)->postJson('/api/workout-logs', [
        'completed_at' => now()->toDateString(),
    ]);

    $this->assertDatabaseHas('workout_logs', [
        'user_id' => $client->id,
        'coach_id' => $coach->id,
    ]);
});

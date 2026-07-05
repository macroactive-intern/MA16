<?php

use App\Models\User;
use App\Models\WorkoutLog;

it('client sees only their own logs', function () {
    $client = User::factory()->client()->create();
    $other = User::factory()->client()->create();

    WorkoutLog::factory()->create(['user_id' => $client->id]);
    WorkoutLog::factory()->create(['user_id' => $other->id]);

    $this->actingAs($client)
        ->getJson('/api/workout-logs')
        ->assertOk()
        ->assertJsonCount(1);
});

it('client cannot view another clients log', function () {
    $client = User::factory()->client()->create();
    $other = User::factory()->client()->create();
    $log = WorkoutLog::factory()->create(['user_id' => $other->id]);

    $this->actingAs($client)
        ->getJson("/api/workout-logs/{$log->id}")
        ->assertForbidden();
});

it('coach sees logs for their assigned clients', function () {
    $coach = User::factory()->coach()->create();
    $client = User::factory()->client($coach)->create();
    $unassigned = User::factory()->client()->create();

    WorkoutLog::factory()->create(['user_id' => $client->id, 'coach_id' => $coach->id]);
    WorkoutLog::factory()->create(['user_id' => $unassigned->id]);

    $this->actingAs($coach)
        ->getJson('/api/workout-logs')
        ->assertOk()
        ->assertJsonCount(1);
});

it('coach does not see another coachs client logs', function () {
    $coachA = User::factory()->coach()->create();
    $coachB = User::factory()->coach()->create();
    $client = User::factory()->client($coachB)->create();

    WorkoutLog::factory()->create(['user_id' => $client->id, 'coach_id' => $coachB->id]);

    $this->actingAs($coachA)
        ->getJson('/api/workout-logs')
        ->assertOk()
        ->assertJsonCount(0);
});

it('coach can view an assigned client log', function () {
    $coach = User::factory()->coach()->create();
    $client = User::factory()->client($coach)->create();
    $log = WorkoutLog::factory()->create(['user_id' => $client->id, 'coach_id' => $coach->id]);

    $this->actingAs($coach)
        ->getJson("/api/workout-logs/{$log->id}")
        ->assertOk();
});

it('coach cannot view an unassigned client log', function () {
    $coach = User::factory()->coach()->create();
    $otherCoach = User::factory()->coach()->create();
    $client = User::factory()->client($otherCoach)->create();
    $log = WorkoutLog::factory()->create(['user_id' => $client->id, 'coach_id' => $otherCoach->id]);

    $this->actingAs($coach)
        ->getJson("/api/workout-logs/{$log->id}")
        ->assertForbidden();
});

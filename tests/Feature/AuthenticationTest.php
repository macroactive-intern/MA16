<?php

it('guests cannot list workout logs', function () {
    $this->getJson('/api/workout-logs')->assertUnauthorized();
});

it('guests cannot create workout logs', function () {
    $this->postJson('/api/workout-logs', [])->assertUnauthorized();
});

it('guests cannot view a workout log', function () {
    $this->getJson('/api/workout-logs/1')->assertUnauthorized();
});

it('guests cannot update a workout log', function () {
    $this->putJson('/api/workout-logs/1', [])->assertUnauthorized();
});

it('guests cannot delete a workout log', function () {
    $this->deleteJson('/api/workout-logs/1')->assertUnauthorized();
});

it('guests cannot access workout stats', function () {
    $this->getJson('/api/workout-stats')->assertUnauthorized();
});

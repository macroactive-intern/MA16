## Overview

I am going to build a Laravel JSON API that lets clients log completed workouts and lets coaches view logs for their assigned clients.

The main focus of this task is authorization using Laravel Policy classes. I will implement the authorization rules in `WorkoutLogPolicy` instead of putting role checks or `abort(403)` calls directly in the controllers.

Clients can:

* List their own workout logs.
* Create workout logs for themselves.
* View their own workout logs.
* Update their own workout logs if the log is within the last 7 days.
* Delete their own workout logs.
* View their own workout stats.

Coaches can:

* List logs for their assigned clients.
* View a single log for one of their assigned clients.

Coaches cannot:

* Create workout logs.
* Update client workout logs.
* Delete client workout logs.
* Access another coach’s clients’ logs.

## Project setup

I will start from a new Laravel project:

```bash
composer create-project laravel/laravel workout-log
cd workout-log
```

Then I will install Sanctum:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

I will configure SQLite in `.env`:

```env
DB_CONNECTION=sqlite
```

I will create the SQLite database file:

```bash
touch database/database.sqlite
```

I will also install Pest if it is not already installed:

```bash
composer require pestphp/pest --dev --with-all-dependencies
php artisan pest:install
```

All API routes will be protected with `auth:sanctum`.

## Data model

### Users table changes

The existing `users` table needs role support.

I will add:

| Column   | Type               | Notes                                 |
| -------- | ------------------ | ------------------------------------- |
| role     | string             | allowed values: `coach`, `client`     |
| coach_id | foreignId nullable | points to the client’s assigned coach |

I am adding `coach_id` to the `users` table because the brief says coaches can view their own clients’ logs, but it does not provide a separate coach-client relationship table.

This means:

* A user with `role = client` may have a `coach_id`.
* A user with `role = coach` should usually have `coach_id = null`.
* Each client belongs to one coach for this task.

The migration will look roughly like this:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('role')->default('client')->after('password');
    $table->foreignId('coach_id')
        ->nullable()
        ->after('role')
        ->constrained('users')
        ->nullOnDelete();
});
```

### `workout_logs` table

I will create a `workout_logs` table.

| Column           | Type                           | Notes                                              |
| ---------------- | ------------------------------ | -------------------------------------------------- |
| id               | bigIncrements                  | primary key                                        |
| user_id          | foreignId                      | the client who owns the log                        |
| coach_id         | foreignId                      | the client’s assigned coach at the time of logging |
| completed_at     | date                           | date the workout was completed                     |
| program_name     | string(100), nullable          | optional program name                              |
| notes            | text, nullable                 | optional notes                                     |
| duration_minutes | unsignedSmallInteger, nullable | optional workout duration                          |
| deleted_at       | timestamp, nullable            | soft delete marker                                 |
| timestamps       | timestamps                     | created_at and updated_at                          |

The migration will look roughly like this:

```php
Schema::create('workout_logs', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('coach_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->date('completed_at');
    $table->string('program_name', 100)->nullable();
    $table->text('notes')->nullable();
    $table->unsignedSmallInteger('duration_minutes')->nullable();

    $table->softDeletes();
    $table->timestamps();
});
```

I will allow `coach_id` to be nullable because a client might not have an assigned coach. If a client has no coach, the log can still exist, but no coach will be able to view it.

## Models and relationships

### `User` model

The `User` model will have:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'coach_id',
];
```

Relationships:

```php
public function coach()
{
    return $this->belongsTo(User::class, 'coach_id');
}

public function clients()
{
    return $this->hasMany(User::class, 'coach_id');
}

public function workoutLogs()
{
    return $this->hasMany(WorkoutLog::class, 'user_id');
}

public function coachedWorkoutLogs()
{
    return $this->hasMany(WorkoutLog::class, 'coach_id');
}
```

### `WorkoutLog` model

The `WorkoutLog` model will use `SoftDeletes`.

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkoutLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'coach_id',
        'completed_at',
        'program_name',
        'notes',
        'duration_minutes',
    ];

    protected $casts = [
        'completed_at' => 'date',
        'duration_minutes' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
```

## Factories

I will update `UserFactory` with role states:

```php
public function coach(): static
{
    return $this->state(fn () => [
        'role' => 'coach',
        'coach_id' => null,
    ]);
}

public function client(?User $coach = null): static
{
    return $this->state(fn () => [
        'role' => 'client',
        'coach_id' => $coach?->id,
    ]);
}
```

I will create a `WorkoutLogFactory` that can create logs for a client and copy the client’s `coach_id`.

Example factory fields:

```php
return [
    'user_id' => User::factory()->client(),
    'coach_id' => null,
    'completed_at' => now()->toDateString(),
    'program_name' => fake()->randomElement(['Strength', 'Hypertrophy', 'Mobility']),
    'notes' => fake()->sentence(),
    'duration_minutes' => fake()->numberBetween(20, 120),
];
```

When writing tests, I will usually pass `user_id` and `coach_id` directly so the test setup is clear.

## Routes

All routes will go in `routes/api.php` inside the `auth:sanctum` middleware group.

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/workout-logs', [WorkoutLogController::class, 'index']);
    Route::post('/workout-logs', [WorkoutLogController::class, 'store']);
    Route::get('/workout-logs/{workoutLog}', [WorkoutLogController::class, 'show']);
    Route::put('/workout-logs/{workoutLog}', [WorkoutLogController::class, 'update']);
    Route::delete('/workout-logs/{workoutLog}', [WorkoutLogController::class, 'destroy']);

    Route::get('/workout-stats', [WorkoutStatsController::class, 'show']);
});
```

I will use route model binding for individual workout logs.

I will not include soft-deleted logs in route model binding. Deleted logs should not be viewable through normal API requests.

## Endpoints and behavior

### `GET /api/workout-logs`

Controller method: `WorkoutLogController@index`

Authorization:

```php
$this->authorize('viewAny', WorkoutLog::class);
```

Behavior:

* If the authenticated user is a client, return logs where `user_id` equals their user ID.
* If the authenticated user is a coach, return logs where `coach_id` equals their user ID.
* Soft-deleted logs are excluded by default.
* Results can be ordered by `completed_at` descending.

Example logic:

```php
$query = WorkoutLog::query()->latest('completed_at');

if ($user->role === 'client') {
    $query->where('user_id', $user->id);
}

if ($user->role === 'coach') {
    $query->where('coach_id', $user->id);
}

return response()->json($query->get());
```

The policy allows access to the listing endpoint, but the query still needs to be scoped so users only receive logs they are allowed to see.

### `POST /api/workout-logs`

Controller method: `WorkoutLogController@store`

Authorization:

```php
$this->authorize('create', WorkoutLog::class);
```

Behavior:

* Only clients can create logs.
* The request cannot choose `user_id`.
* The request cannot choose `coach_id`.
* `user_id` is set from the authenticated user.
* `coach_id` is copied from the authenticated client’s `coach_id`.

Validation:

```php
[
    'completed_at' => ['required', 'date'],
    'program_name' => ['nullable', 'string', 'max:100'],
    'notes' => ['nullable', 'string'],
    'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:65535'],
]
```

I will not allow users to pass `user_id` or `coach_id` in the request body because clients should only create logs for themselves.

### `GET /api/workout-logs/{workoutLog}`

Controller method: `WorkoutLogController@show`

Authorization:

```php
$this->authorize('view', $workoutLog);
```

Behavior:

* A client can view their own log.
* A coach can view a log where `coach_id` matches their own user ID.
* Other users receive 403.

### `PUT /api/workout-logs/{workoutLog}`

Controller method: `WorkoutLogController@update`

Authorization:

```php
$this->authorize('update', $workoutLog);
```

Behavior:

* Only clients can update.
* The client must own the log.
* The existing saved `completed_at` date must be within the last 7 days.
* A log from 3 days ago should return 200.
* A log from 8 days ago should return 403.
* Coaches cannot update any client logs.

Validation:

```php
[
    'program_name' => ['sometimes', 'nullable', 'string', 'max:100'],
    'notes' => ['sometimes', 'nullable', 'string'],
    'duration_minutes' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:65535'],
]
```

I will not allow `completed_at` to be updated after creation. This avoids a client changing an old log date to make it editable again or changing a recent log into an old one by mistake.

### `DELETE /api/workout-logs/{workoutLog}`

Controller method: `WorkoutLogController@destroy`

Authorization:

```php
$this->authorize('delete', $workoutLog);
```

Behavior:

* Only the client who owns the log can delete it.
* Coaches cannot delete logs.
* Other clients cannot delete logs.
* The delete action uses soft deletes.
* The response can be `204 No Content`.

After deleting, a direct database check should show:

* The row still exists.
* `deleted_at` is not null.

Example:

```sql
SELECT * FROM workout_logs WHERE id = ?;
```

The returned row should still be present with a `deleted_at` timestamp.

### `GET /api/workout-stats`

Controller method: `WorkoutStatsController@show`

Authorization:

```php
$this->authorize('viewStats', WorkoutLog::class);
```

I will add a `viewStats` method to `WorkoutLogPolicy` so this endpoint still uses the policy instead of inline role checks.

Behavior:

* Only clients can access this endpoint.
* It returns stats for the authenticated client only.
* It excludes soft-deleted logs.
* It excludes other clients’ logs.

Response shape:

```json
{
    "total_logs": 5,
    "total_duration": 240,
    "most_logged_program": "Strength"
}
```

Stats logic:

* `total_logs`: count of the client’s non-deleted workout logs.
* `total_duration`: sum of `duration_minutes`, treating null values as 0.
* `most_logged_program`: most common non-null `program_name`.

If there are no logs, return:

```json
{
    "total_logs": 0,
    "total_duration": 0,
    "most_logged_program": null
}
```

## Policy design

I will create:

```bash
php artisan make:policy WorkoutLogPolicy --model=WorkoutLog
```

The policy will contain these methods:

* `viewAny(User $user): bool`
* `view(User $user, WorkoutLog $workoutLog): bool`
* `create(User $user): bool`
* `update(User $user, WorkoutLog $workoutLog): bool`
* `delete(User $user, WorkoutLog $workoutLog): bool`
* `viewStats(User $user): bool`

### `viewAny`

`viewAny` is used for the list endpoint.

It answers:

> Can this user access the workout log listing endpoint?

It does not receive a specific `WorkoutLog` model because it is not checking one record.

Example:

```php
public function viewAny(User $user): bool
{
    return in_array($user->role, ['client', 'coach'], true);
}
```

The controller still scopes the query after this check:

* Clients only get their own logs.
* Coaches only get logs where they are the assigned coach.

### `view`

`view` is used for one specific workout log.

It answers:

> Can this user view this exact workout log?

Example:

```php
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
```

### `create`

`create` is used when a user tries to create a workout log.

Example:

```php
public function create(User $user): bool
{
    return $user->role === 'client';
}
```

Only clients can create workout logs.

### `update`

`update` is used when a user tries to update a specific workout log.

It receives:

* The authenticated `User`
* The `WorkoutLog` being updated

It returns `true` or `false`.

Rules:

* User must be a client.
* User must own the log.
* The existing `completed_at` date must be within the last 7 days.

Example:

```php
public function update(User $user, WorkoutLog $workoutLog): bool
{
    return $user->role === 'client'
        && $workoutLog->user_id === $user->id
        && $workoutLog->completed_at->greaterThanOrEqualTo(now()->subDays(7)->startOfDay());
}
```

I am using `completed_at`, not `created_at`, because the brief says older workout logs are locked based on the log date.

Exactly 7 days ago will be allowed because I am treating “within the last 7 days” as inclusive.

### `delete`

`delete` is used when a user tries to delete a specific workout log.

Example:

```php
public function delete(User $user, WorkoutLog $workoutLog): bool
{
    return $user->role === 'client'
        && $workoutLog->user_id === $user->id;
}
```

Coaches cannot delete logs.

### `viewStats`

`viewStats` is used for the personal stats endpoint.

Example:

```php
public function viewStats(User $user): bool
{
    return $user->role === 'client';
}
```

This keeps the stats endpoint authorization inside the policy instead of checking the role directly in the controller.

## Policy registration approach

I will register the policy explicitly in `AuthServiceProvider`.

Example:

```php
use App\Models\WorkoutLog;
use App\Policies\WorkoutLogPolicy;

protected $policies = [
    WorkoutLog::class => WorkoutLogPolicy::class,
];
```

Explicit registration means Laravel is directly told:

> `WorkoutLog` uses `WorkoutLogPolicy`.

Auto-discovery means Laravel tries to guess the policy based on naming conventions, for example:

* Model: `App\Models\WorkoutLog`
* Policy: `App\Policies\WorkoutLogPolicy`

I am choosing explicit registration because the brief specifically asks me to document whether I used explicit registration or auto-discovery, and the acceptance criteria says the policy should be registered in `AuthServiceProvider`.

If the Laravel version does not include `AuthServiceProvider` by default, I will create it and register it properly so the policy mapping is visible for review.

## Controller authorization style

Controllers will use Laravel’s authorization helpers.

Allowed:

```php
$this->authorize('view', $workoutLog);
$this->authorize('update', $workoutLog);
$this->authorize('delete', $workoutLog);
$this->authorize('create', WorkoutLog::class);
$this->authorize('viewAny', WorkoutLog::class);
```

Not allowed:

```php
abort(403);
```

Not allowed:

```php
if ($user->role !== 'client') {
    abort(403);
}
```

I will avoid inline authorization conditions in controllers. Controllers can still use the user role for query scoping after authorization, but the allow/deny decision should live in the policy.

## Libraries and packages

### Laravel

Used as the main framework for routing, controllers, models, migrations, validation, and testing.

### Laravel Sanctum

Used for API authentication.

All endpoints will require:

```php
auth:sanctum
```

### Pest

Used for feature tests.

The brief requires policy behavior to be tested, including the 7-day update window. Pest will make the tests readable and grouped by behavior.

### SQLite

Used as the database for local development and testing because the brief asks for SQLite configuration.

### Eloquent SoftDeletes

Used for `DELETE /api/workout-logs/{id}`.

This lets the app set `deleted_at` instead of permanently removing the row.

## Validation approach

For creating workout logs:

```php
[
    'completed_at' => ['required', 'date'],
    'program_name' => ['nullable', 'string', 'max:100'],
    'notes' => ['nullable', 'string'],
    'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:65535'],
]
```

For updating workout logs:

```php
[
    'program_name' => ['sometimes', 'nullable', 'string', 'max:100'],
    'notes' => ['sometimes', 'nullable', 'string'],
    'duration_minutes' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:65535'],
]
```

I will not allow update requests to change:

* `user_id`
* `coach_id`
* `completed_at`

This keeps ownership and the 7-day lock stable.

## Testing approach

I will write feature tests first and let them fail before implementing the code.

### Authentication tests

* Guest cannot list workout logs.
* Guest cannot create workout logs.
* Guest cannot view a workout log.
* Guest cannot update a workout log.
* Guest cannot delete a workout log.
* Guest cannot access workout stats.

### Client list/view tests

* Client sees their own logs only.
* Client cannot view another client’s log.

### Coach list/view tests

* Coach sees assigned clients’ logs.
* Coach does not see another coach’s client logs.
* Coach can view an assigned client’s log.
* Coach cannot view an unassigned client’s log.

### Create tests

* Client can create a workout log.
* Coach cannot create a workout log.
* `user_id` is taken from the authenticated user.
* `coach_id` is copied from the authenticated client’s assigned coach.

### Update tests

* Client can update their own log from 3 days ago.
* Client cannot update their own log from 8 days ago.
* Client cannot update another client’s log.
* Coach cannot update a client’s log.
* A log from exactly 7 days ago is allowed because I am treating the boundary as inclusive.

### Delete tests

* Client can soft-delete their own log.
* Client cannot delete another client’s log.
* Coach cannot delete a client’s log.
* Row remains in the database after delete.
* `deleted_at` is set after delete.

### Stats tests

* Client gets total log count.
* Client gets total duration.
* Client gets most-logged program.
* Soft-deleted logs are excluded.
* Other clients’ logs are excluded.
* Coach cannot access personal stats.

### Policy coverage

The tests should exercise:

* `viewAny`
* `view`
* `create`
* `update`
* `delete`
* `viewStats`

## Edge cases

### Client has no coach

A client may have `coach_id = null`.

The client can still create workout logs.

Those logs will have `coach_id = null`, so no coach can view them.

### Coach tries to create a workout log

Blocked by `WorkoutLogPolicy::create()`.

Expected response: 403.

### Coach tries to update or delete a client log

Blocked by `WorkoutLogPolicy::update()` or `WorkoutLogPolicy::delete()`.

Expected response: 403.

### Client tries to access another client’s log

Blocked by `WorkoutLogPolicy::view()`, `update()`, or `delete()` depending on the action.

Expected response: 403.

### Client updates an 8-day-old log

The controller calls:

```php
$this->authorize('update', $workoutLog);
```

Laravel resolves `WorkoutLogPolicy::update()`.

The policy checks:

* user is a client
* user owns the log
* `completed_at` is greater than or equal to `now()->subDays(7)->startOfDay()`

Because the log is from 8 days ago, the policy returns `false`.

Laravel returns 403.

### Exactly 7 days ago

I will allow exactly 7 days ago.

Reason:

The brief says logs can be edited within the last 7 days. I am treating the 7-day boundary as inclusive.

### Soft-deleted logs

Soft-deleted logs should not appear in normal list responses.

Soft-deleted logs should not appear in stats.

Route model binding should not include soft-deleted logs.

A direct database check after delete should still find the row with `deleted_at` set.

### Null duration

If `duration_minutes` is null, it should count as 0 for total duration.

### Null program name

Null `program_name` values should not be counted for most-logged program.

If all program names are null, `most_logged_program` should be null.

### Tie for most-logged program

The brief does not define tie behavior.

I will keep the query simple and return the first most common program. If needed, I can make the tie-break deterministic by ordering alphabetically after count.

### Future completed dates

The brief does not say whether future workout logs are allowed.

I will probably prevent future dates with validation:

```php
'completed_at' => ['required', 'date', 'before_or_equal:today']
```

This makes sense because users are logging completed workouts.

### Updating `completed_at`

I will not allow `completed_at` to be updated.

Reason:

The update lock is based on `completed_at`. Allowing clients to change it could create confusing behavior or let them bypass the locked-window rule.

### Authorization failure response

Unauthorized authenticated users should receive 403.

Unauthenticated users should receive 401.

## Decisions made from unclear parts of the brief

### Coach-client relationship

The brief does not define a coach-client assignment table.

Decision:

I will add `coach_id` to the `users` table.

Reason:

It is the simplest way to support “coach can view their clients’ logs” without adding an extra pivot table that the brief did not ask for.

### Policy registration

Decision:

I will use explicit registration in `AuthServiceProvider`.

Reason:

It is easy to show in code review and directly satisfies the brief.

### `viewAny` vs `view`

Decision:

* Use `viewAny` for `GET /api/workout-logs`.
* Use `view` for `GET /api/workout-logs/{id}`.

Reason:

`viewAny` checks access to a collection endpoint. `view` checks access to one model instance.

### Stats authorization

Decision:

I will add `viewStats` to `WorkoutLogPolicy`.

Reason:

The brief says all authorization should be handled by policy classes. A dedicated policy method keeps the stats endpoint from using inline role checks.

### Update window

Decision:

The 7-day window is based on `completed_at`.

Reason:

The log represents the date the workout was completed, and the brief says older logs are locked.

### Soft delete

Decision:

Use Laravel’s `SoftDeletes` trait and `$table->softDeletes()`.

Reason:

The acceptance criteria requires that a direct database check still shows the deleted row with `deleted_at` set.

## Manual verification checklist

Before finishing, I will run:

```bash
php artisan test
```

I will also check routes:

```bash
php artisan route:list
```

I will search for forbidden inline authorization shortcuts:

```bash
grep -R "abort(403" app routes tests
grep -R "Gate::" app routes
```

I will also manually confirm:

* A client can create a log.
* A coach cannot create a log.
* A client can update a 3-day-old log.
* A client cannot update an 8-day-old log.
* A coach cannot update or delete logs.
* Delete sets `deleted_at`.
* Stats exclude soft-deleted logs.

## Final implementation order

1. Set up Laravel, SQLite, Sanctum, and Pest.
2. Add role and coach assignment fields to users.
3. Create `workout_logs` migration.
4. Create `WorkoutLog` model and relationships.
5. Update `User` relationships.
6. Create factories.
7. Write failing feature tests.
8. Create `WorkoutLogPolicy`.
9. Register policy in `AuthServiceProvider`.
10. Create routes and controllers.
11. Implement workout log endpoints.
12. Implement stats endpoint.
13. Run tests and fix failures.
14. Add terminal output to `BEFORE-AFTER.md`.
15. Final check for no inline `abort(403)` authorization.

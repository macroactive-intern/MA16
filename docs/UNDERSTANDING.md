What is the task asking me to build?

This task is asking me to build a Laravel API for logging completed workouts.

Clients can create workout logs for themselves, view their own logs, update recent logs, delete their own logs, and view personal workout stats.

Coaches can view workout logs for clients assigned to them, but coaches cannot create, update, or delete workout logs.

The main focus of this task is authorization. All authorization rules must be handled through a Laravel Policy class called WorkoutLogPolicy. Controllers should not contain inline authorization logic like abort(403) or custom role checks.

The workout log update rule is important: a client can only update their own workout log if the log is from within the last 7 days. Older logs are locked and should return 403 when update is attempted.

-------------------------------------------------------

What needs to be built?

Required setup includes:

                - Create a new Laravel project called workout-log
                - Install Laravel Sanctum
                - Configure SQLite
                - Add a role column to the users table
                - Create a workout_logs table
                - Create a WorkoutLog model
                - Create a WorkoutLogPolicy
                - Register the policy
                - Create API routes
                - Create controller logic
                - Write feature tests for all authorization paths
                - Document terminal output in BEFORE-AFTER.md

-------------------------------------------------------

Data model

The main table is workout_logs.

Each workout log belongs to a client user through user_id.

Each workout log also stores the assigned coach through coach_id.

Required columns:

                - id
                - user_id
                - coach_id
                - completed_at
                - program_name
                - notes
                - duration_minutes
                - deleted_at
                - created_at
                - updated_at

The model should use soft deletes so that deleting a log does not remove the row from the database. Instead, the deleted_at column should be set.

--------------------------------------------------------------------------

Endpoints

GET /api/workout-logs

Lists workout logs.

Expected behavior:

Client receives only their own logs.
Coach receives logs for their assigned clients.
Other users’ logs should not be visible.

-------------------------------------------------------

POST /api/workout-logs

Creates a completed workout log.

Expected behavior:

Only clients can create workout logs.
Coaches cannot create workout logs.
The created log belongs to the authenticated client.
The coach_id should represent that client’s assigned coach.

-------------------------------------------------------

GET /api/workout-logs/{id}

Shows a single workout log.

Expected behavior:

A client can only view their own log.
A coach can only view logs where they are the assigned coach.
Other users should receive 403.

This endpoint should use the policy view method.

-------------------------------------------------------

PUT /api/workout-logs/{id}

Updates a workout log.

Expected behavior:

Only the client who owns the log can update it.
Coaches cannot update logs.
Other clients cannot update logs.
The log can only be updated if completed_at is within the last 7 days.
Logs older than 7 days are locked and should return 403.

This endpoint should use the policy update method.

-------------------------------------------------------

DELETE /api/workout-logs/{id}

Soft-deletes a workout log.

Expected behavior:

Only the client who owns the log can delete it.
Coaches cannot delete logs.
Other clients cannot delete logs.
The row should still exist in the database after deletion.
deleted_at should be set.

This endpoint should use the policy delete method.

-------------------------------------------------------

GET /api/workout-stats

Returns personal workout stats for the authenticated client.

Expected behavior:

Returns the client’s own stats only.
Should probably include:
total logs
total duration
most-logged program
Coaches should not use this endpoint unless the brief says otherwise.

--------------------------------------------------------------------------

What inputs does it take?

Workout log creation and update accepts:

                - completed_at
                - program_name
                - notes
                - duration_minutes

Validation assumptions:

        - completed_at should be a valid date.
        - program_name is nullable and max 100 characters.
        - notes is nullable text.
        - duration_minutes is nullable and should be a positive integer within the unsigned small integer range.
        - user_id should not be accepted from the request body because the authenticated client should own the log.
        - coach_id should not be accepted directly unless the brief requires it, because it should come from the client’s assigned coach relationship.

-------------------------------------------------------

What does it return?

The API should return JSON.

Expected responses:

- List endpoint returns an array or paginated list of workout logs.
- Create endpoint returns the created workout log.
- Show endpoint returns one workout log.
- Update endpoint returns the updated workout log.
- Delete endpoint returns a success response, probably 204 No Content.
- Stats endpoint returns totals and most-logged program.

-------------------------------------------------------

Policy registration

I will register the policy explicitly in AuthServiceProvider.

Example:

        protected $policies = [
            WorkoutLog::class => WorkoutLogPolicy::class,
        ];

Explicit registration means Laravel is directly told which policy class belongs to which model.

Auto-discovery means Laravel guesses the policy based on naming conventions, for example:

                Model: App\Models\WorkoutLog
                Policy: App\Policies\WorkoutLogPolicy

The brief specifically asks me to document the registration approach, and the acceptance criteria says the policy is registered in AuthServiceProvider, so explicit registration is the clearest option.

-------------------------------------------------------

What is viewAny?

viewAny is the policy method used when authorizing access to a list of models.

For this task, viewAny should be used for:

GET /api/workout-logs

-------------------------------------------

It is different from view.

view is used when checking access to one specific WorkoutLog model.

- viewAny(User $user) checks whether the user can access the list endpoint.

- view(User $user, WorkoutLog $workoutLog) checks whether the user can view one specific log.

Even after viewAny allows access to the list endpoint, the query still needs to be scoped correctly so clients only see their own logs and coaches only see their clients’ logs.

-------------------------------------------

How the 7-day update window is checked

The 7-day update window should be checked inside the WorkoutLogPolicy::update() method.

The method should look at:

                        the authenticated user
                        the workout log being updated
                        the log’s completed_at date

The logic should be:

                        user must be a client
                        user must own the log
                        completed_at must be within the last 7 days

The brief says a log from 3 days ago can be updated and a log from 8 days ago cannot be updated.

The exact boundary for a log from exactly 7 days ago is not stated. My assumption is that exactly 7 days ago should still be allowed because the wording says “within the last 7 days.”

--------------------------------------------------------------------------

How is a client assigned to a coach?

For this task, I will likely keep it simple and add a nullable coach_id to the users table so each client can belong to one coach. Then, when a client creates a workout log, the log’s coach_id is copied from the client’s assigned coach.

-------------------------------------------

Should coaches be able to use GET /api/workout-stats?

Coaches already have access to their clients’ logs through /api/workout-logs, but no coach-specific stats endpoint is requested.

-------------------------------------------

Should clients be able to update completed_at?

The policy checks the existing saved completed_at value before update.
The request may allow updating completed_at, but validation should prevent confusing behavior if needed.
A safer option is to not allow completed_at to be changed after creation.

I will likely avoid allowing completed_at updates unless it is needed

-------------------------------------------

Is the 7-day rule based on completed_at or created_at

The data model has completed_at, so I assume the lock is based on completed_at, not created_at.

-------------------------------------------

Is exactly 7 days ago allowed?

3 days ago is allowed.
8 days ago is blocked.

any day within the 7 days ago is allowed. so exactly 7 days ago is allowed.

-------------------------------------------

Should list results be paginated?

I will probably return a simple collection

-------------------------------------------

Should soft-deleted logs appear in lists or stats?

Since the delete endpoint uses soft delete, Laravel’s default behavior will exclude soft-deleted rows from normal queries.

-------------------------------------------

Should program_name be required for most-logged program?

For most-logged program, I will ignore null program names. If there are no named programs, the value can be null.

-------------------------------------------

What should happen if there is a tie for most-logged program?

Return the first result by count, then maybe alphabetically or by latest created. I will keep this simple and document it in the approach.

-------------------------------------------

Should coaches be able to view deleted logs?

No. Soft-deleted logs should be hidden from normal API responses.

-------------------------------------------

Should authorization failures return 403 or 404?

I expect 403 for unauthorized actions, so I will use Laravel authorization responses that return 403.

-------------------------------------------

Should there be a separate create policy method?

For POST /api/workout-logs, I will implement and call WorkoutLogPolicy::create(User $user) to ensure only clients can create logs.

-------------------------------------------

Should route model binding include soft-deleted logs?

No. Normal route model binding should ignore soft-deleted logs.


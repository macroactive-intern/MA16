Step 1

    Project set up
                1. Start new Laravel project
                2. connect to Github repo
                                                                                                    10 mins

----------------------------------------------------------------------------------------------------------------

Step 2

    Documentation
                1. Write out the Understand.md
                2. Write out the Time Estimate.md
                3. Add the Ai Time estimate to the Estimate.md
                4. Write out the Aproach.md
                                                                                                        120 mins

----------------------------------------------------------------------------------------------------------------

Step 3

    Finish Project set up
                1. Install dependencies
                2. Install Sanctum
                3. Install Pest
                4. Confirm API/auth setup
                                                                                                    20 mins

----------------------------------------------------------------------------------------------------------------

Step 4

    create tests first

            1. Authentication tests
                Guests cannot list logs.
                Guests cannot create logs.
                Guests cannot view logs.
                Guests cannot update logs.
                Guests cannot delete logs.
                Guests cannot access stats.
            2. Client list/view tests
                Client sees own logs only.
                Client cannot view another client’s log.
            3. Coach list/view tests
                Coach sees assigned clients’ logs.
                Coach does not see another coach’s client logs.
                Coach can view assigned client log.
                Coach cannot view unassigned client log.
            4. Create tests
                Client can create log.
                Coach cannot create log.
                user_id is taken from authenticated user.
                coach_id is set correctly.
            5. Update tests
                Client can update own log from 3 days ago.
                Client cannot update own log from 8 days ago.
                Client cannot update another client’s log.
                Coach cannot update client log.
                Test exact 7-day boundary if you choose to define it.
            6. Delete tests
                Client can soft-delete own log.
                Client cannot delete another client’s log.
                Coach cannot delete client log.
                Row remains in database after delete.
                deleted_at is set.
            7. Stats tests
                Client gets total log count.
                Client gets total duration.
                Client gets most-logged program.
                Soft-deleted logs are excluded.
                Other clients’ logs are excluded.
            
            Decide test factories needed
                UserFactory with role states:
                    coach
                    client
                WorkoutLogFactory
                                            120 mins

----------------------------------------------------------------------------------------------------------------

Step 5

    Database and models

        1. Update users table
                - Add:

                    role
                    likely coach_id for client assignment
                
                - Create migration for user role and - - coach assignment.
                - Add default role, probably client.
                - Add nullable coach_id.
                - Add foreign key from coach_id to users.id
        2. Create workout logs migration
                Columns:
                        id
                        user_id
                        coach_id
                        completed_at
                        program_name
                        notes
                        duration_minutes
                        deleted_at
                        timestamps
        3. Create WorkoutLog model
                Add fillable fields.
                Add casts:
                        completed_at as date
                        duration_minutes as integer
                Add SoftDeletes.
                Add relationships:
                                user
                                coach
        4. Update User model

            Add fillable fields:
                                role
                                coach_id
            Add relationships:
                                coach
                                clients
                                workoutLogs
                                coachedWorkoutLogs
        5. Create factories

                            Add coach() state to UserFactory.
                            Add client() state to UserFactory.
                            Create WorkoutLogFactory.

        6. Run migrations
                                            40 mins

----------------------------------------------------------------------------------------------------------------

Step 6

    Policy implementation

                1. Create policy
                            Allows authenticated users with role:
                                        client
                                        coach
                2. Implement viewAny
                            Rules:
                                Client can view own log.
                                Coach can view logs
                                
                                where coach_id matches coach user ID.

                3. Implement view
                            Rules:
                                Only clients can create workout logs.
                4. Implement create
                            Rules:
                                Only clients can create workout logs.

                5. Implement update
                            Rules:
                                Only clients.
                                Must own the log.
                                Log must be within 7 days based on completed_at
                
                6. Register policy
                        In AuthServiceProvider:
                                protected $policies = [
                                    WorkoutLog::class => WorkoutLogPolicy::class,
                                ];
                                            45 mins

----------------------------------------------------------------------------------------------------------------

Step 7

    Routes and controller

                1. Create controller
                2. Add routes in routes/api.php
                3. Implement index
                4. Implement store
                5. Implement show
                6. Implement update
                7. Implement destroy
                                            30 mins

----------------------------------------------------------------------------------------------------------------

Step 8

    Workout stats endpoint

                1. Create controller
                2. Implement client-only access
                3. Calculate stats
                4. Handle edge cases
                                            25 mins

----------------------------------------------------------------------------------------------------------------

Step 9

    Policy coverage tests

                Make sure all methods are used:
                            viewAny
                            view
                            create
                            update
                            delete
                                            30 mins

----------------------------------------------------------------------------------------------------------------

Step 9

    Run Tests
                                                                                                    20 mins

----------------------------------------------------------------------------------------------------------------

Step 10

    Fix any failing tests
                                                                                                    40 mins

----------------------------------------------------------------------------------------------------------------

Step 11

    Manual test
                                                                                                    45 mins

----------------------------------------------------------------------------------------------------------------

Step 12 

    BEFORE-AFTER.md
                                                                                                    30 mins
----------------------------------------------------------------------------------------------------------------

                                                                                                    9.6 hrs

---------------------------------------------------------------------------------------------------------------- 
AI Estimate

Estimated total: 9 hrs 30 mins

This task is larger than a basic CRUD API because the main requirement is not just storing workout logs, but enforcing all authorization through a Laravel Policy and proving that behavior with tests.

The highest-risk areas are:

Correctly scoping coach/client access.
Making sure all authorization uses WorkoutLogPolicy.
Testing every policy method.
Handling the 7-day update lock based on completed_at.
Confirming soft deletes with a direct database check.
Building the stats endpoint while excluding soft-deleted logs and other clients’ data.
AI Breakdown
Step	Estimate
Project setup	15 mins
Documentation	100 mins
Laravel/Sanctum/Pest setup	30 mins
Test planning and first failing tests	135 mins
Database, models, relationships, factories	55 mins
Policy implementation and registration	55 mins
Routes and workout log controller	55 mins
Workout stats endpoint	40 mins
Policy coverage tests	40 mins
Run tests	15 mins
Fix failing tests	60 mins
Manual testing	40 mins
BEFORE-AFTER.md	30 mins
Total

670 mins including buffer — about 11 hrs 10 mins

A more realistic working estimate without heavy buffer is 9 hrs 30 mins. The safe quoted estimate is 10–11 hours because policy-based authorization bugs can take extra time to debug, especially around viewAny, route model binding, soft deletes, and date-window checks.

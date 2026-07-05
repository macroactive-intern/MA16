 PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   FAIL  Tests\Feature\AuthenticationTest
  ⨯ it guests cannot list workout logs                                                                                                0.31s  
  ⨯ it guests cannot create workout logs                                                                                              0.01s  
  ⨯ it guests cannot view a workout log                                                                                               0.01s  
  ⨯ it guests cannot update a workout log                                                                                             0.01s  
  ⨯ it guests cannot delete a workout log                                                                                             0.01s  
  ⨯ it guests cannot access workout stats                                                                                             0.01s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                                                     0.03s  

   FAIL  Tests\Feature\WorkoutLogCreateTest
  ⨯ it client can create a workout log                                                                                                0.08s  
  ⨯ it coach cannot create a workout log                                                                                              0.01s  
  ⨯ it user_id is taken from the authenticated client                                                                                 0.02s  
  ⨯ it coach_id is copied from the clients assigned coach                                                                             0.02s  

   FAIL  Tests\Feature\WorkoutLogDeleteTest
  ⨯ it client can soft-delete their own log                                                                                           0.02s  
  ⨯ it client cannot delete another clients log                                                                                       0.01s  
  ⨯ it coach cannot delete a client log                                                                                               0.01s  
  ⨯ it row remains in the database after soft delete                                                                                  0.01s  
  ⨯ it deleted_at is set after soft delete                                                                                            0.01s  

   FAIL  Tests\Feature\WorkoutLogListTest
  ⨯ it client sees only their own logs                                                                                                0.02s  
  ⨯ it client cannot view another clients log                                                                                         0.02s  
  ⨯ it coach sees logs for their assigned clients                                                                                     0.02s  
  ⨯ it coach does not see another coachs client logs                                                                                  0.01s  
  ⨯ it coach can view an assigned client log                                                                                          0.01s  
  ⨯ it coach cannot view an unassigned client log                                                                                     0.01s  

   FAIL  Tests\Feature\WorkoutLogUpdateTest
  ⨯ it client can update their own log from 3 days ago                                                                                0.02s  
  ⨯ it client cannot update their own log from 8 days ago                                                                             0.02s  
  ⨯ it client cannot update another clients log                                                                                       0.02s  
  ⨯ it coach cannot update a client log                                                                                               0.02s  
  ⨯ it client can update a log from exactly 7 days ago                                                                                0.02s  

   FAIL  Tests\Feature\WorkoutStatsTest
  ⨯ it client gets total log count                                                                                                    0.02s  
  ⨯ it client gets total duration                                                                                                     0.02s  
  ⨯ it client gets most logged program                                                                                                0.02s  
  ⨯ it soft-deleted logs are excluded from stats                                                                                      0.02s  
  ⨯ it other clients logs are excluded from stats                                                                                     0.02s  
  ⨯ it coach cannot access workout stats                                                                                              0.02s  
  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthenticationTest > it guests cannot list workout logs                                                             
  Expected response status code [401] but received 404.
Failed asserting that 404 is identical to 401.

  at tests\Feature\AuthenticationTest.php:4
      1▕ <?php
      2▕ 
      3▕ it('guests cannot list workout logs', function () {
  ➜   4▕     $this->getJson('/api/workout-logs')->assertUnauthorized();
      5▕ });
      6▕ 
      7▕ it('guests cannot create workout logs', function () {
      8▕     $this->postJson('/api/workout-logs', [])->assertUnauthorized();
      9▕ });

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthenticationTest > it guests cannot create workout logs                                                           
  Expected response status code [401] but received 404.
Failed asserting that 404 is identical to 401.

  at tests\Feature\AuthenticationTest.php:8
      4▕     $this->getJson('/api/workout-logs')->assertUnauthorized();
      5▕ });
      6▕ 
      7▕ it('guests cannot create workout logs', function () {
  ➜   8▕     $this->postJson('/api/workout-logs', [])->assertUnauthorized();
      9▕ });
     10▕ 
     11▕ it('guests cannot view a workout log', function () {
     12▕     $this->getJson('/api/workout-logs/1')->assertUnauthorized();

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthenticationTest > it guests cannot view a workout log                                                            
  Expected response status code [401] but received 404.
Failed asserting that 404 is identical to 401.

  at tests\Feature\AuthenticationTest.php:12
      8▕     $this->postJson('/api/workout-logs', [])->assertUnauthorized();
      9▕ });
     10▕ 
     11▕ it('guests cannot view a workout log', function () {
  ➜  12▕     $this->getJson('/api/workout-logs/1')->assertUnauthorized();
     13▕ });
     14▕ 
     15▕ it('guests cannot update a workout log', function () {
     16▕     $this->putJson('/api/workout-logs/1', [])->assertUnauthorized();

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthenticationTest > it guests cannot update a workout log                                                          
  Expected response status code [401] but received 404.
Failed asserting that 404 is identical to 401.

  at tests\Feature\AuthenticationTest.php:16
     12▕     $this->getJson('/api/workout-logs/1')->assertUnauthorized();
     13▕ });
     14▕ 
     15▕ it('guests cannot update a workout log', function () {
  ➜  16▕     $this->putJson('/api/workout-logs/1', [])->assertUnauthorized();
     17▕ });
     18▕ 
     19▕ it('guests cannot delete a workout log', function () {
     20▕     $this->deleteJson('/api/workout-logs/1')->assertUnauthorized();

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthenticationTest > it guests cannot delete a workout log                                                          
  Expected response status code [401] but received 404.
Failed asserting that 404 is identical to 401.

  at tests\Feature\AuthenticationTest.php:20
     16▕     $this->putJson('/api/workout-logs/1', [])->assertUnauthorized();
     17▕ });
     18▕ 
     19▕ it('guests cannot delete a workout log', function () {
  ➜  20▕     $this->deleteJson('/api/workout-logs/1')->assertUnauthorized();
     21▕ });
     22▕ 
     23▕ it('guests cannot access workout stats', function () {
     24▕     $this->getJson('/api/workout-stats')->assertUnauthorized();

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\AuthenticationTest > it guests cannot access workout stats                                                          
  Expected response status code [401] but received 404.
Failed asserting that 404 is identical to 401.

  at tests\Feature\AuthenticationTest.php:24
     20▕     $this->deleteJson('/api/workout-logs/1')->assertUnauthorized();
     21▕ });
     22▕ 
     23▕ it('guests cannot access workout stats', function () {
  ➜  24▕     $this->getJson('/api/workout-stats')->assertUnauthorized();
     25▕ });
     26▕

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogCreateTest > it client can create a workout log                                          QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Ms. Josianne Schaefer DVM, porter.oconnell@example.com, 2026-07-05 21:29:46, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, FgaFgYM2yr, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogCreateTest > it coach cannot create a workout log                                        QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Efren Kuhic, kasey82@example.net, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, sppXVBb4co, coach, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogCreateTest > it user_id is taken from the authenticated client                           QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Amalia Steuber, katelynn.schumm@example.org, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, VSqsRMrIHf, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogCreateTest > it coach_id is copied from the clients assigned coach                       QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Ronaldo Prosacco Sr., jacobi.carolyne@example.org, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, D4I2LdEMeQ, coach, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogDeleteTest > it client can soft-delete their own log                                     QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Prof. Jordon Wuckert V, carleton.okeefe@example.com, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, 0aRQnq57oN, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogDeleteTest > it client cannot delete another clients log                                 QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Prof. Hailie DuBuque, mertz.ted@example.org, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, OEfLh4U6mv, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogDeleteTest > it coach cannot delete a client log                                         QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Carolanne Leuschke, lesley10@example.com, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, OYIsO8DeiI, coach, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogDeleteTest > it row remains in the database after soft delete                            QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Tanner Johnson, brenna76@example.org, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, M2WuuGAtbN, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogDeleteTest > it deleted_at is set after soft delete                                      QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Abdullah Satterfield, hassie.kutch@example.net, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, jzzypWjIdd, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogListTest > it client sees only their own logs                                            QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Junius Barton, bode.pierce@example.net, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, 7cM08xzQAQ, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogListTest > it client cannot view another clients log                                     QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Garrison Hammes, werner.white@example.org, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, QmQooQgENW, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogListTest > it coach sees logs for their assigned clients                                 QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Mr. Clinton Jenkins PhD, boris57@example.net, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, zqgLPyuAkW, coach, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogListTest > it coach does not see another coachs client logs                              QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Kale Thiel, idouglas@example.com, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, V3rYIM045z, coach, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogListTest > it coach can view an assigned client log                                      QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Nola Ankunding, xhirthe@example.com, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, VCf5cirHpJ, coach, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogListTest > it coach cannot view an unassigned client log                                 QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Dr. Gudrun Legros, barrows.rhea@example.net, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, Ig7sCoXBCf, coach, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogUpdateTest > it client can update their own log from 3 days ago                          QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Mikayla Schowalter, lwatsica@example.com, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, DSx0iy7ESr, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogUpdateTest > it client cannot update their own log from 8 days ago                       QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Candida Gulgowski III, jstroman@example.net, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, bIkDip6UbC, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogUpdateTest > it client cannot update another clients log                                 QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Catalina Olson, rau.ryan@example.org, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, 2uVKlNtzet, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogUpdateTest > it coach cannot update a client log                                         QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Zackary Braun MD, schneider.ines@example.com, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, Mbj1q9dTaq, coach, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutLogUpdateTest > it client can update a log from exactly 7 days ago                          QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Candelario Ernser, bhill@example.com, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, 0HePm83rOt, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutStatsTest > it client gets total log count                                                  QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Dr. Norbert Sporer III, murphy.hodkiewicz@example.org, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, Ix0X8vePxN, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutStatsTest > it client gets total duration                                                   QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Macie Rowe PhD, cyrus93@example.net, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, l73C6i3S5G, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutStatsTest > it client gets most logged program                                              QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Hudson Kling, reuben69@example.net, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, sCTyUBOKoc, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutStatsTest > it soft-deleted logs are excluded from stats                                    QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Shanon Mohr, jacobson.cyrus@example.com, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, PAyOpJrHIq, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutStatsTest > it other clients logs are excluded from stats                                   QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Cynthia Strosin, mraz.jordi@example.net, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, G8MVwFMDmR, client, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827

  ─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\WorkoutStatsTest > it coach cannot access workout stats                                            QueryException   
  SQLSTATE[HY000]: General error: 1 table users has no column named role (Connection: sqlite, Database: :memory:, SQL: insert into "users" ("name", "email", "email_verified_at", "password", "remember_token", "role", "coach_id", "updated_at", "created_at") values (Ashlynn Price, rashawn11@example.net, 2026-07-05 21:29:47, $2y$04$Rtf78jZwJuMYvEBEkgdolOX.FLF84bZA2Yec5uQqgoam4faX7SWA2, KiJe5Has4I, coach, ?, 2026-07-05 21:29:47, 2026-07-05 21:29:47))

  at vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
    574▕             if ($this->pretending()) {
    575▕                 return true;
    576▕             }
    577▕ 
  ➜ 578▕             $statement = $this->getPdo()->prepare($query);
    579▕ 
    580▕             $this->bindValues($statement, $this->prepareBindings($bindings));
    581▕ 
    582▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\Connection.php:578
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:827


  Tests:    32 failed, 2 passed (8 assertions)
  Duration: 1.36s



  -----------------------------------------------------------------------------------------------------------------------------------------

  After

  -----------------------------------------------------------------------------------------------------------------------------------------

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\AuthenticationTest
  ✓ it guests cannot list workout logs                                                                                                0.31s  
  ✓ it guests cannot create workout logs                                                                                              0.01s  
  ✓ it guests cannot view a workout log                                                                                               0.01s  
  ✓ it guests cannot update a workout log                                                                                             0.01s  
  ✓ it guests cannot delete a workout log                                                                                             0.01s  
  ✓ it guests cannot access workout stats                                                                                             0.02s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                                                     0.03s  

   PASS  Tests\Feature\WorkoutLogCreateTest
  ✓ it client can create a workout log                                                                                                0.05s  
  ✓ it coach cannot create a workout log                                                                                              0.02s  
  ✓ it user_id is taken from the authenticated client                                                                                 0.02s  
  ✓ it coach_id is copied from the clients assigned coach                                                                             0.02s  

   PASS  Tests\Feature\WorkoutLogDeleteTest
  ✓ it client can soft-delete their own log                                                                                           0.02s  
  ✓ it client cannot delete another clients log                                                                                       0.02s  
  ✓ it coach cannot delete a client log                                                                                               0.02s  
  ✓ it row remains in the database after soft delete                                                                                  0.01s  
  ✓ it deleted_at is set after soft delete                                                                                            0.02s  

   PASS  Tests\Feature\WorkoutLogListTest
  ✓ it client sees only their own logs                                                                                                0.02s  
  ✓ it client cannot view another clients log                                                                                         0.01s  
  ✓ it coach sees logs for their assigned clients                                                                                     0.02s  
  ✓ it coach does not see another coachs client logs                                                                                  0.01s  
  ✓ it coach can view an assigned client log                                                                                          0.02s  
  ✓ it coach cannot view an unassigned client log                                                                                     0.02s  

   PASS  Tests\Feature\WorkoutLogUpdateTest
  ✓ it client can update their own log from 3 days ago                                                                                0.02s  
  ✓ it client cannot update their own log from 8 days ago                                                                             0.02s  
  ✓ it client cannot update another clients log                                                                                       0.02s  
  ✓ it coach cannot update a client log                                                                                               0.02s  
  ✓ it client can update a log from exactly 7 days ago                                                                                0.02s  

   PASS  Tests\Feature\WorkoutStatsTest
  ✓ it client gets total log count                                                                                                    0.02s  
  ✓ it client gets total duration                                                                                                     0.02s  
  ✓ it client gets most logged program                                                                                                0.02s  
  ✓ it soft-deleted logs are excluded from stats                                                                                      0.01s  
  ✓ it other clients logs are excluded from stats                                                                                     0.02s  
  ✓ it coach cannot access workout stats                                                                                              0.02s  

  Tests:    34 passed (45 assertions)
  Duration: 1.13s
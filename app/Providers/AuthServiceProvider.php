<?php

namespace App\Providers;

use App\Models\WorkoutLog;
use App\Policies\WorkoutLogPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        WorkoutLog::class => WorkoutLogPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

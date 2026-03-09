<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
     public function boot()
    {
        $this->registerPolicies();

        // Define Gates for role-based access control
        Gate::define('view-admin-features', function ($user) {
            return in_array($user->role, ['admin', 'superadmin']);
        });

        Gate::define('view-user-bookings', function ($user) {
            return in_array($user->role, ['user', 'admin', 'superadmin']);
        });

        Gate::define('delete-bookings', function ($user) {
            return $user->role === 'superadmin';
        });

        Gate::define('manage-users', function ($user) {
            return $user->role === 'superadmin';
        });

        Gate::define('view-system-settings', function ($user) {
            return $user->role === 'superadmin';
        });

        Gate::define('moderate-content', function ($user) {
            return in_array($user->role, ['admin', 'superadmin']);
        });

        Gate::define('view-earnings', function ($user) {
            return in_array($user->role, ['admin', 'superadmin']);
        });
    }
}

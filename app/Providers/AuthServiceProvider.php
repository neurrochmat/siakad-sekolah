<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\School;
use App\Policies\SchoolPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        School::class => SchoolPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "admin" role all permissions
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        // Define gates for specific actions
        Gate::define('manage-users', function ($user) {
            return $user->hasAnyPermission(['read_user', 'create_user', 'update_user', 'delete_user']);
        });

        Gate::define('manage-roles', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-permissions', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-schools', function ($user) {
            return $user->hasAnyPermission(['read_school', 'create_school', 'update_school', 'delete_school']);
        });
    }
}

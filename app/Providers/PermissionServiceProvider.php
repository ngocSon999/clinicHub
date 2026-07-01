<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, string $ability) {
            $currentTeam = getPermissionsTeamId();

            setPermissionsTeamId(null);
            $isSuperAdmin = $user->hasRole('super_admin');

            setPermissionsTeamId($currentTeam);

            return $isSuperAdmin ? true : null;
        });
    }
}

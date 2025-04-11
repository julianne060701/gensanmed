<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('view-admin-menu', function ($user) {
            return $user->hasRole('administrator');
        });
    
        Gate::define('view-engineer-menu', function ($user) {
            return $user->hasRole('engineer');
        });

        Gate::define('view-purchaser-menu', function ($user) {
            return $user->hasRole('purchaser');
        });

        Gate::define('view-head-menu', function ($user) {
            return $user->hasRole('head'); // Added new gate for Head role
        });

        Gate::define('view-HIMS-menu', function ($user) {
            return $user->hasRole('HIMS');
        });

        Gate::define('view-mmo-menu', function ($user) {
            return $user->hasRole('mmo');
        });

    }
}

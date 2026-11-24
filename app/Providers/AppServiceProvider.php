<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('manage-users', fn (User $user): bool => $user->isAdmin());

        Gate::define('manage-products', fn (User $user): bool => $user->isAdmin());

        Gate::define(
            'manage-inventory',
            fn (User $user): bool => $user->hasRole(UserRole::Admin, UserRole::Vendedor)
        );

        Gate::define(
            'view-inventory',
            fn (User $user): bool => $user->hasRole(UserRole::Admin, UserRole::Vendedor)
        );
    }
}

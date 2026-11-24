<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as BladeView;

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

        View::composer('layouts.app', function (BladeView $view): void {
            if (! Auth::check()) {
                return;
            }

            $lowStockProducts = Product::query()
                ->lowStock()
                ->orderBy('stock')
                ->limit(5)
                ->get(['id', 'sku', 'name', 'stock', 'min_stock']);

            $view->with([
                'alertLowStockCount' => Product::query()->lowStock()->count(),
                'alertLowStockProducts' => $lowStockProducts,
            ]);
        });
    }
}

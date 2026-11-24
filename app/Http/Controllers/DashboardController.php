<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalProducts = Product::query()->count();
        $activeProducts = Product::query()->where('is_active', true)->count();
        $totalUnits = (int) Product::query()->sum('stock');
        $lowStockProducts = Product::query()
            ->with('category')
            ->lowStock()
            ->orderBy('stock')
            ->limit(6)
            ->get();
        $lowStockCount = Product::query()->lowStock()->count();

        $recentMovements = InventoryMovement::query()
            ->with(['product', 'user'])
            ->latest('id')
            ->limit(6)
            ->get();

        $totalMovements = InventoryMovement::query()->count();

        return view('dashboard', [
            'totalUsers' => User::query()->count(),
            'adminCount' => User::query()->where('role', UserRole::Admin)->count(),
            'vendedorCount' => User::query()->where('role', UserRole::Vendedor)->count(),
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'totalUnits' => $totalUnits,
            'lowStockCount' => $lowStockCount,
            'lowStockProducts' => $lowStockProducts,
            'recentMovements' => $recentMovements,
            'totalMovements' => $totalMovements,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAndSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_seeder_populates_sample_catalog_and_is_idempotent(): void
    {
        $this->seed();

        $this->assertSame(5, Category::query()->count());
        $this->assertSame(10, Product::query()->count());
        $this->assertSame(20, InventoryMovement::query()->count());

        $this->seed();

        $this->assertSame(5, Category::query()->count());
        $this->assertSame(10, Product::query()->count());
        $this->assertSame(20, InventoryMovement::query()->count());
    }

    public function test_dashboard_displays_inventory_metrics_low_stock_alerts_and_recent_movements(): void
    {
        $this->seed();

        $vendedor = User::query()->where('email', 'vendedor@inventario.test')->firstOrFail();

        $this->actingAs($vendedor)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Panel de inventario')
            ->assertSee('Café Molido Tostado 250 g')
            ->assertSee('Yogurt Griego Natural 500 g')
            ->assertDontSee('Administrar usuarios y roles');
    }
}

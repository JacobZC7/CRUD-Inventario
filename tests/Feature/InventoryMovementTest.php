<?php

namespace Tests\Feature;

use App\Enums\MovementType;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryMovementTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendedor_and_admin_can_view_movements_and_record_entrada_salida_ajuste(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->for($category)->create([
            'stock' => 10,
            'min_stock' => 3,
        ]);

        $this->actingAs($vendedor)
            ->get(route('movements.index'))
            ->assertOk();

        $this->actingAs($vendedor)
            ->get(route('movements.create', ['product_id' => $product->id]))
            ->assertOk();

        $this->actingAs($vendedor)
            ->post(route('movements.store'), [
                'product_id' => $product->id,
                'type' => MovementType::Entrada->value,
                'quantity' => 5,
                'reason' => 'Reposición proveedor',
            ])
            ->assertRedirect(route('movements.index'));

        $this->assertSame(15, $product->fresh()->stock);

        $this->actingAs($vendedor)
            ->post(route('movements.store'), [
                'product_id' => $product->id,
                'type' => MovementType::Salida->value,
                'quantity' => 4,
                'reason' => 'Venta mostrador',
                'redirect_to' => 'product',
            ])
            ->assertRedirect(route('products.show', $product));

        $this->assertSame(11, $product->fresh()->stock);

        $this->actingAs($vendedor)
            ->post(route('movements.store'), [
                'product_id' => $product->id,
                'type' => MovementType::Ajuste->value,
                'quantity' => 0,
                'reason' => 'Merma total en inventario físico',
            ])
            ->assertRedirect(route('movements.index'));

        $this->assertSame(0, $product->fresh()->stock);

        $lastMovement = InventoryMovement::query()->latest('id')->firstOrFail();
        $this->assertSame(MovementType::Ajuste, $lastMovement->type);
        $this->assertSame(11, $lastMovement->previous_stock);
        $this->assertSame(0, $lastMovement->new_stock);
        $this->assertSame(11, $lastMovement->quantity);
    }

    public function test_salida_exceeding_available_stock_fails_validation_and_preserves_stock(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $product = Product::factory()->create(['stock' => 3]);

        $this->actingAs($vendedor)
            ->from(route('movements.create', ['product_id' => $product->id]))
            ->post(route('movements.store'), [
                'product_id' => $product->id,
                'type' => MovementType::Salida->value,
                'quantity' => 6,
                'reason' => 'Intento sin stock',
            ])
            ->assertRedirect(route('movements.create', ['product_id' => $product->id]))
            ->assertSessionHasErrors('quantity');

        $this->assertSame(3, $product->fresh()->stock);
        $this->assertSame(0, InventoryMovement::query()->count());
    }

    public function test_ajuste_matching_current_stock_fails_validation(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['stock' => 8]);

        $this->actingAs($admin)
            ->from(route('movements.create', ['product_id' => $product->id]))
            ->post(route('movements.store'), [
                'product_id' => $product->id,
                'type' => MovementType::Ajuste->value,
                'quantity' => 8,
            ])
            ->assertRedirect(route('movements.create', ['product_id' => $product->id]))
            ->assertSessionHasErrors('quantity');

        $this->assertSame(8, $product->fresh()->stock);
        $this->assertSame(0, InventoryMovement::query()->count());
    }
}

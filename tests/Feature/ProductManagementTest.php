<?php

namespace Tests\Feature;

use App\Enums\MovementType;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendedor_can_view_products_index_with_filters_and_product_detail(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $bebidas = Category::factory()->create(['name' => 'Bebidas']);
        $lacteos = Category::factory()->create(['name' => 'Lácteos']);

        $match = Product::factory()->for($bebidas)->create([
            'sku' => 'BEB-101',
            'name' => 'Agua Tónica 500 ml',
            'stock' => 2,
            'min_stock' => 5,
        ]);

        $other = Product::factory()->for($lacteos)->create([
            'sku' => 'LAC-101',
            'name' => 'Queso Fresco 400 g',
            'stock' => 25,
            'min_stock' => 5,
        ]);

        $this->actingAs($vendedor)
            ->get(route('products.index', [
                'search' => 'BEB-101',
                'category_id' => $bebidas->id,
                'stock_status' => 'low',
            ]))
            ->assertOk()
            ->assertSee($match->name)
            ->assertDontSee($other->name);

        $this->actingAs($vendedor)
            ->get(route('products.show', $match))
            ->assertOk()
            ->assertSee($match->sku)
            ->assertSee($match->name);
    }

    public function test_vendedor_cannot_create_edit_or_delete_products(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->for($category)->create();

        $this->actingAs($vendedor)->get(route('products.create'))->assertForbidden();

        $this->actingAs($vendedor)->post(route('products.store'), [
            'category_id' => $category->id,
            'sku' => 'TST-001',
            'name' => 'Producto Test',
            'cost' => 1.00,
            'price' => 2.00,
            'stock' => 10,
            'min_stock' => 2,
        ])->assertForbidden();

        $this->actingAs($vendedor)->get(route('products.edit', $product))->assertForbidden();

        $this->actingAs($vendedor)->put(route('products.update', $product), [
            'category_id' => $category->id,
            'sku' => $product->sku,
            'name' => 'Modificado',
            'cost' => 1.00,
            'price' => 2.00,
            'min_stock' => 2,
        ])->assertForbidden();

        $this->actingAs($vendedor)->delete(route('products.destroy', $product))->assertForbidden();
    }

    public function test_admin_can_create_product_with_uppercase_sku_and_initial_movement(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'category_id' => $category->id,
            'sku' => '  beb-900 ',
            'name' => 'Té Verde Frío 500 ml',
            'description' => 'Bebida refrescante.',
            'cost' => 0.85,
            'price' => 1.50,
            'stock' => 15,
            'min_stock' => 5,
            'is_active' => '1',
        ]);

        $product = Product::query()->where('sku', 'BEB-900')->firstOrFail();

        $response->assertRedirect(route('products.show', $product));
        $this->assertSame(15, $product->stock);
        $this->assertTrue($product->is_active);

        $movement = InventoryMovement::query()->where('product_id', $product->id)->firstOrFail();
        $this->assertSame(MovementType::Entrada, $movement->type);
        $this->assertSame(15, $movement->quantity);
        $this->assertSame(0, $movement->previous_stock);
        $this->assertSame(15, $movement->new_stock);
    }

    public function test_admin_can_update_and_delete_product_without_overwriting_stock(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->for($category)->create([
            'sku' => 'DES-500',
            'stock' => 20,
        ]);

        $this->actingAs($admin)
            ->put(route('products.update', $product), [
                'category_id' => $category->id,
                'sku' => 'des-501',
                'name' => 'Nombre Actualizado',
                'description' => 'Nueva descripción',
                'cost' => 1.25,
                'price' => 2.50,
                'stock' => 999,
                'min_stock' => 4,
            ])
            ->assertRedirect(route('products.show', $product));

        $product->refresh();
        $this->assertSame('DES-501', $product->sku);
        $this->assertSame('Nombre Actualizado', $product->name);
        $this->assertSame(20, $product->stock);
        $this->assertFalse($product->is_active);

        $this->actingAs($admin)
            ->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}

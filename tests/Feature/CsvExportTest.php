<?php

namespace Tests\Feature;

use App\Enums\MovementType;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CsvExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_export_products_or_movements(): void
    {
        $this->get(route('products.export'))
            ->assertRedirect(route('login'));

        $this->get(route('movements.export'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_export_filtered_products_csv_safely(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $category = Category::factory()->create(['name' => 'Bebidas']);

        Product::factory()->for($category)->create([
            'sku' => 'BEB-010',
            'name' => '=FormulaPelicula',
            'stock' => 2,
            'min_stock' => 5,
        ]);

        Product::factory()->for($category)->create([
            'sku' => 'BEB-020',
            'name' => 'Agua Pura 1 L',
            'stock' => 30,
            'min_stock' => 5,
        ]);

        $response = $this->actingAs($vendedor)
            ->get(route('products.export', ['stock_status' => 'low']));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));

        $csv = $response->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringContainsString('SKU;Nombre;', $csv);
        $this->assertStringContainsString('BEB-010', $csv);
        $this->assertStringContainsString("'=FormulaPelicula", $csv);
        $this->assertStringNotContainsString('BEB-020', $csv);
    }

    public function test_authenticated_user_can_export_filtered_movements_csv(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'sku' => 'DES-100',
            'name' => 'Arroz Blanco',
            'stock' => 15,
        ]);

        InventoryMovement::factory()->for($product)->for($admin)->create([
            'type' => MovementType::Entrada,
            'quantity' => 10,
            'previous_stock' => 5,
            'new_stock' => 15,
            'reason' => '+Ingreso sospechoso',
        ]);

        InventoryMovement::factory()->for($product)->for($admin)->create([
            'type' => MovementType::Salida,
            'quantity' => 2,
            'previous_stock' => 15,
            'new_stock' => 13,
            'reason' => 'Venta normal',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('movements.export', [
                'product_id' => $product->id,
                'type' => MovementType::Entrada->value,
            ]));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));

        $csv = $response->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringContainsString('Fecha;SKU;Producto;Tipo;Cantidad', $csv);
        $this->assertStringContainsString("'+Ingreso sospechoso", $csv);
        $this->assertStringNotContainsString('Venta normal', $csv);
    }
}

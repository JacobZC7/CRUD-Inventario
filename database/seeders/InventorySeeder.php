<?php

namespace Database\Seeders;

use App\Enums\MovementType;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@inventario.test')->first();
        $vendedor = User::query()->where('email', 'vendedor@inventario.test')->first() ?? $admin;

        if (! $admin) {
            return;
        }

        $categoriesData = [
            ['name' => 'Bebidas', 'description' => 'Aguas, jugos, refrescos y bebidas frías.'],
            ['name' => 'Lácteos', 'description' => 'Leches, yogures, quesos y mantequillas.'],
            ['name' => 'Despensa', 'description' => 'Arroz, pastas, aceites, granos y enlatados.'],
            ['name' => 'Snacks', 'description' => 'Galletas, frutos secos y pasabocas.'],
            ['name' => 'Limpieza', 'description' => 'Detergentes, desinfectantes y cuidado del hogar.'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['name']] = Category::query()->updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                ],
            );
        }

        $productsData = [
            ['sku' => 'BEB-001', 'category' => 'Bebidas', 'name' => 'Agua Mineral 600 ml', 'cost' => 0.45, 'price' => 0.90, 'stock' => 48, 'min_stock' => 12],
            ['sku' => 'BEB-002', 'category' => 'Bebidas', 'name' => 'Jugo de Naranja Natural 1 L', 'cost' => 1.80, 'price' => 2.95, 'stock' => 18, 'min_stock' => 8],
            ['sku' => 'LAC-001', 'category' => 'Lácteos', 'name' => 'Leche Entera UHT 1 L', 'cost' => 1.10, 'price' => 1.75, 'stock' => 30, 'min_stock' => 10],
            ['sku' => 'LAC-002', 'category' => 'Lácteos', 'name' => 'Yogurt Griego Natural 500 g', 'cost' => 2.10, 'price' => 3.40, 'stock' => 4, 'min_stock' => 6],
            ['sku' => 'DES-001', 'category' => 'Despensa', 'name' => 'Arroz Blanco Extra 1 kg', 'cost' => 1.05, 'price' => 1.65, 'stock' => 52, 'min_stock' => 15],
            ['sku' => 'DES-002', 'category' => 'Despensa', 'name' => 'Aceite Vegetal 900 ml', 'cost' => 2.25, 'price' => 3.50, 'stock' => 22, 'min_stock' => 8],
            ['sku' => 'DES-003', 'category' => 'Despensa', 'name' => 'Café Molido Tostado 250 g', 'cost' => 3.10, 'price' => 4.95, 'stock' => 0, 'min_stock' => 5],
            ['sku' => 'SNK-001', 'category' => 'Snacks', 'name' => 'Almendras Tostadas 150 g', 'cost' => 2.40, 'price' => 3.90, 'stock' => 14, 'min_stock' => 5],
            ['sku' => 'SNK-002', 'category' => 'Snacks', 'name' => 'Galletas Integrales Avena', 'cost' => 0.95, 'price' => 1.60, 'stock' => 3, 'min_stock' => 8],
            ['sku' => 'LIM-001', 'category' => 'Limpieza', 'name' => 'Detergente Líquido 1.8 L', 'cost' => 3.80, 'price' => 5.90, 'stock' => 16, 'min_stock' => 6],
        ];

        $seededAnyMovement = InventoryMovement::query()->exists();

        foreach ($productsData as $item) {
            $product = Product::query()->updateOrCreate(
                ['sku' => $item['sku']],
                [
                    'category_id' => $categories[$item['category']]->id,
                    'name' => $item['name'],
                    'description' => 'Producto de catálogo MiniMercado.',
                    'cost' => $item['cost'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'min_stock' => $item['min_stock'],
                    'is_active' => true,
                ],
            );

            if (! $seededAnyMovement) {
                if ($item['stock'] > 0) {
                    InventoryMovement::query()->create([
                        'product_id' => $product->id,
                        'user_id' => $admin->id,
                        'type' => MovementType::Entrada,
                        'quantity' => $item['stock'] + 4,
                        'previous_stock' => 0,
                        'new_stock' => $item['stock'] + 4,
                        'reason' => 'Carga inicial de almacén',
                    ]);

                    InventoryMovement::query()->create([
                        'product_id' => $product->id,
                        'user_id' => $vendedor->id,
                        'type' => MovementType::Salida,
                        'quantity' => 4,
                        'previous_stock' => $item['stock'] + 4,
                        'new_stock' => $item['stock'],
                        'reason' => 'Ventas mostrador del día',
                    ]);
                } else {
                    InventoryMovement::query()->create([
                        'product_id' => $product->id,
                        'user_id' => $admin->id,
                        'type' => MovementType::Entrada,
                        'quantity' => 5,
                        'previous_stock' => 0,
                        'new_stock' => 5,
                        'reason' => 'Carga inicial de almacén',
                    ]);

                    InventoryMovement::query()->create([
                        'product_id' => $product->id,
                        'user_id' => $vendedor->id,
                        'type' => MovementType::Salida,
                        'quantity' => 5,
                        'previous_stock' => 5,
                        'new_stock' => 0,
                        'reason' => 'Agotado en venta de mostrador',
                    ]);
                }
            }
        }
    }
}

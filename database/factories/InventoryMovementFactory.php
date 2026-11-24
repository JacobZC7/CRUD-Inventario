<?php

namespace Database\Factories;

use App\Enums\MovementType;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryMovement>
 */
class InventoryMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $previous = fake()->numberBetween(10, 40);
        $qty = fake()->numberBetween(1, 10);

        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'type' => MovementType::Entrada,
            'quantity' => $qty,
            'previous_stock' => $previous,
            'new_stock' => $previous + $qty,
            'reason' => 'Reposición de inventario',
        ];
    }
}

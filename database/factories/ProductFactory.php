<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cost = fake()->randomFloat(2, 1, 25);

        return [
            'category_id' => Category::factory(),
            'sku' => strtoupper(fake()->unique()->bothify('PRD-####')),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'cost' => $cost,
            'price' => round($cost * 1.35, 2),
            'stock' => fake()->numberBetween(10, 80),
            'min_stock' => 5,
            'is_active' => true,
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 3,
            'min_stock' => 5,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
            'min_stock' => 5,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendedor_cannot_access_category_management_routes(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $category = Category::factory()->create();

        $this->actingAs($vendedor)
            ->get(route('categories.index'))
            ->assertForbidden();

        $this->actingAs($vendedor)
            ->post(route('categories.store'), [
                'name' => 'Panadería',
            ])
            ->assertForbidden();

        $this->actingAs($vendedor)
            ->put(route('categories.update', $category), [
                'name' => 'Editada',
            ])
            ->assertForbidden();

        $this->actingAs($vendedor)
            ->delete(route('categories.destroy', $category))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_empty_category(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => 'Congelados',
                'description' => 'Productos refrigerados y congelados.',
            ])
            ->assertRedirect(route('categories.index'));

        $category = Category::query()->where('name', 'Congelados')->firstOrFail();
        $this->assertSame('congelados', $category->slug);

        $this->actingAs($admin)
            ->from(route('categories.index'))
            ->put(route('categories.update', $category), [
                'name' => 'Helados y Congelados',
                'description' => 'Nueva descripción.',
            ])
            ->assertRedirect(route('categories.index'));

        $category->refresh();
        $this->assertSame('Helados y Congelados', $category->name);
        $this->assertSame('helados-y-congelados', $category->slug);

        $this->actingAs($admin)
            ->from(route('categories.index'))
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_category_with_products(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        Product::factory()->for($category)->create();

        $this->actingAs($admin)
            ->from(route('categories.index'))
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}

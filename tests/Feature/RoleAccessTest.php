<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_user_management(): void
    {
        $this->get(route('admin.users.index'))
            ->assertRedirect(route('login'));

        $target = User::factory()->create();

        $this->patch(route('admin.users.role.update', $target), [
            'role' => UserRole::Admin->value,
        ])->assertRedirect(route('login'));
    }

    public function test_vendedor_receives_forbidden_on_admin_routes(): void
    {
        $vendedor = User::factory()->vendedor()->create();
        $target = User::factory()->vendedor()->create();

        $this->actingAs($vendedor)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs($vendedor)
            ->patch(route('admin.users.role.update', $target), [
                'role' => UserRole::Admin->value,
            ])
            ->assertForbidden();
    }

    public function test_admin_can_view_users_and_update_another_users_role(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->vendedor()->create();

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee($target->email);

        $this->actingAs($admin)
            ->patch(route('admin.users.role.update', $target), [
                'role' => UserRole::Admin->value,
            ])
            ->assertRedirect();

        $this->assertSame(UserRole::Admin, $target->fresh()->role);
    }

    public function test_admin_cannot_demote_themselves(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->from(route('admin.users.index'))
            ->patch(route('admin.users.role.update', $admin), [
                'role' => UserRole::Vendedor->value,
            ])
            ->assertSessionHasErrors('role');

        $this->assertSame(UserRole::Admin, $admin->fresh()->role);
    }

    public function test_role_gates_evaluate_correctly_for_each_role(): void
    {
        $admin = User::factory()->admin()->create();
        $vendedor = User::factory()->vendedor()->create();

        $this->assertTrue(Gate::forUser($admin)->allows('manage-users'));
        $this->assertTrue(Gate::forUser($admin)->allows('manage-products'));
        $this->assertTrue(Gate::forUser($admin)->allows('manage-inventory'));
        $this->assertTrue(Gate::forUser($admin)->allows('view-inventory'));

        $this->assertFalse(Gate::forUser($vendedor)->allows('manage-users'));
        $this->assertFalse(Gate::forUser($vendedor)->allows('manage-products'));
        $this->assertTrue(Gate::forUser($vendedor)->allows('manage-inventory'));
        $this->assertTrue(Gate::forUser($vendedor)->allows('view-inventory'));
    }

    public function test_database_seeder_creates_default_users_for_all_roles(): void
    {
        $this->seed();

        $this->assertSame(UserRole::Admin, User::where('email', 'admin@inventario.test')->firstOrFail()->role);
        $this->assertSame(UserRole::Vendedor, User::where('email', 'vendedor@inventario.test')->firstOrFail()->role);
        $this->assertSame(2, User::query()->count());
    }
}

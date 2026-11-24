<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    public function test_enum_exposes_expected_roles_and_default(): void
    {
        $this->assertSame(['admin', 'vendedor'], UserRole::values());
        $this->assertSame(UserRole::Vendedor, UserRole::default());
        $this->assertSame('Administrador', UserRole::Admin->label());
        $this->assertSame('Vendedor', UserRole::Vendedor->label());
    }

    public function test_user_role_helper_methods_work_with_enums_and_strings(): void
    {
        $admin = new User(['role' => UserRole::Admin]);
        $vendedor = new User(['role' => UserRole::Vendedor]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isVendedor());
        $this->assertTrue($admin->hasRole(UserRole::Admin));
        $this->assertTrue($admin->hasRole('admin', 'vendedor'));
        $this->assertFalse($admin->hasRole('vendedor'));

        $this->assertTrue($vendedor->isVendedor());
        $this->assertTrue($vendedor->hasRole(UserRole::Admin, UserRole::Vendedor));
        $this->assertFalse($vendedor->isAdmin());
        $this->assertFalse($vendedor->hasRole('admin'));
    }
}

<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Vendedor = 'vendedor';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Vendedor => 'Vendedor',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Admin => 'Control total de usuarios, catálogo de productos, stock y movimientos.',
            self::Vendedor => 'Consulta de catálogo, control de stock y registro de movimientos de venta.',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Admin => 'bg-[#0071E3]/10 text-[#005BB5] border-[#0071E3]/20',
            self::Vendedor => 'bg-emerald-50 text-emerald-700 border-emerald-600/20',
        };
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function default(): self
    {
        return self::Vendedor;
    }
}

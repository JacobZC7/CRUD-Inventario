<?php

namespace App\Enums;

enum MovementType: string
{
    case Entrada = 'entrada';
    case Salida = 'salida';
    case Ajuste = 'ajuste';

    public function label(): string
    {
        return match ($this) {
            self::Entrada => 'Entrada',
            self::Salida => 'Salida',
            self::Ajuste => 'Ajuste',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Entrada => 'bg-emerald-50 text-emerald-700 border-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:border-emerald-400/25',
            self::Salida => 'bg-amber-50 text-amber-700 border-amber-600/20 dark:bg-amber-500/15 dark:text-amber-300 dark:border-amber-400/25',
            self::Ajuste => 'bg-[#0071E3]/10 text-[#005BB5] border-[#0071E3]/20 dark:bg-[#0A84FF]/15 dark:text-[#64B5FF] dark:border-[#0A84FF]/30',
        };
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

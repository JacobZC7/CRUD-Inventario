<?php

namespace App\Models;

use App\Enums\MovementType;
use Database\Factories\InventoryMovementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_id',
    'user_id',
    'type',
    'quantity',
    'previous_stock',
    'new_stock',
    'reason',
])]
class InventoryMovement extends Model
{
    /** @use HasFactory<InventoryMovementFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MovementType::class,
            'quantity' => 'integer',
            'previous_stock' => 'integer',
            'new_stock' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function signedDeltaLabel(): string
    {
        if ($this->type === MovementType::Entrada) {
            return '+'.$this->quantity;
        }

        if ($this->type === MovementType::Salida) {
            return '-'.$this->quantity;
        }

        $diff = $this->new_stock - $this->previous_stock;

        if ($diff > 0) {
            return '+'.$diff;
        }

        return (string) $diff;
    }
}

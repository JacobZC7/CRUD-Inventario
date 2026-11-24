<?php

namespace App\Services;

use App\Enums\MovementType;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryMovementService
{
    /**
     * @throws ValidationException
     */
    public function record(
        Product $product,
        User $user,
        MovementType $type,
        int $quantity,
        ?string $reason = null,
    ): InventoryMovement {
        return DB::transaction(function () use ($product, $user, $type, $quantity, $reason): InventoryMovement {
            $lockedProduct = Product::query()
                ->whereKey($product->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $previousStock = $lockedProduct->stock;

            if ($type === MovementType::Entrada) {
                if ($quantity < 1) {
                    throw ValidationException::withMessages([
                        'quantity' => 'La cantidad de entrada debe ser al menos 1.',
                    ]);
                }

                $newStock = $previousStock + $quantity;
                $recordedQuantity = $quantity;
            } elseif ($type === MovementType::Salida) {
                if ($quantity < 1) {
                    throw ValidationException::withMessages([
                        'quantity' => 'La cantidad de salida debe ser al menos 1.',
                    ]);
                }

                if ($quantity > $previousStock) {
                    throw ValidationException::withMessages([
                        'quantity' => "Stock insuficiente. Disponible: {$previousStock}.",
                    ]);
                }

                $newStock = $previousStock - $quantity;
                $recordedQuantity = $quantity;
            } else {
                if ($quantity < 0) {
                    throw ValidationException::withMessages([
                        'quantity' => 'El nuevo stock no puede ser negativo.',
                    ]);
                }

                if ($quantity === $previousStock) {
                    throw ValidationException::withMessages([
                        'quantity' => 'El nuevo stock coincide con el stock actual.',
                    ]);
                }

                $newStock = $quantity;
                $recordedQuantity = abs($newStock - $previousStock);
            }

            $lockedProduct->update([
                'stock' => $newStock,
            ]);

            return InventoryMovement::query()->create([
                'product_id' => $lockedProduct->getKey(),
                'user_id' => $user->getKey(),
                'type' => $type,
                'quantity' => $recordedQuantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reason' => $reason !== null && trim($reason) !== '' ? trim($reason) : null,
            ]);
        });
    }
}

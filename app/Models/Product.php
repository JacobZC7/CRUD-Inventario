<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id',
    'sku',
    'name',
    'description',
    'price',
    'cost',
    'stock',
    'min_stock',
    'is_active',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost' => 'decimal:2',
            'stock' => 'integer',
            'min_stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<InventoryMovement, $this>
     */
    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class)->latest('id');
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->min_stock;
    }

    public function stockStatusLabel(): string
    {
        if ($this->isOutOfStock()) {
            return 'Agotado';
        }

        if ($this->isLowStock()) {
            return 'Stock bajo';
        }

        return 'Disponible';
    }

    public function stockBadgeClasses(): string
    {
        if ($this->isOutOfStock()) {
            return 'bg-rose-50 text-rose-700 border-rose-600/20 dark:bg-rose-500/15 dark:text-rose-300 dark:border-rose-400/25';
        }

        if ($this->isLowStock()) {
            return 'bg-amber-50 text-amber-700 border-amber-600/20 dark:bg-amber-500/15 dark:text-amber-300 dark:border-amber-400/25';
        }

        return 'bg-emerald-50 text-emerald-700 border-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:border-emerald-400/25';
    }

    /**
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }
}

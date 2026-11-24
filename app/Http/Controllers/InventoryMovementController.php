<?php

namespace App\Http\Controllers;

use App\Enums\MovementType;
use App\Http\Requests\StoreInventoryMovementRequest;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Services\InventoryMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryMovementController extends Controller
{
    public function index(Request $request): View
    {
        $productId = $request->integer('product_id');
        $type = (string) $request->string('type');

        $movements = InventoryMovement::query()
            ->with(['product', 'user'])
            ->when($productId > 0, fn ($query) => $query->where('product_id', $productId))
            ->when(MovementType::tryFrom($type) !== null, fn ($query) => $query->where('type', $type))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('movements.index', [
            'movements' => $movements,
            'products' => Product::query()->orderBy('name')->get(['id', 'name', 'sku', 'stock']),
            'movementTypes' => MovementType::cases(),
            'filters' => [
                'product_id' => $productId,
                'type' => $type,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        return view('movements.create', [
            'products' => Product::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'sku', 'stock', 'min_stock']),
            'movementTypes' => MovementType::cases(),
            'selectedProductId' => $request->integer('product_id'),
        ]);
    }

    public function store(StoreInventoryMovementRequest $request, InventoryMovementService $movementService): RedirectResponse
    {
        $validated = $request->validated();

        $product = Product::query()->findOrFail($validated['product_id']);
        $type = MovementType::from($validated['type']);

        $movement = $movementService->record(
            product: $product,
            user: $request->user(),
            type: $type,
            quantity: (int) $validated['quantity'],
            reason: $validated['reason'] ?? null,
        );

        $redirectTo = $request->input('redirect_to') === 'product'
            ? route('products.show', $product)
            : route('movements.index');

        return redirect($redirectTo)->with(
            'status',
            "Movimiento ({$type->label()}) registrado para \"{$product->name}\". Stock actual: {$movement->new_stock}."
        );
    }
}

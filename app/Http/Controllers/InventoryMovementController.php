<?php

namespace App\Http\Controllers;

use App\Enums\MovementType;
use App\Http\Requests\StoreInventoryMovementRequest;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Services\InventoryMovementService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryMovementController extends Controller
{
    public function index(Request $request): View
    {
        $productId = $request->integer('product_id');
        $type = (string) $request->string('type');

        $movements = $this->filteredMovementsQuery($request)
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

    public function export(Request $request): StreamedResponse
    {
        $movements = $this->filteredMovementsQuery($request)
            ->latest('id')
            ->cursor();

        $filename = 'movimientos-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($movements): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Fecha',
                'SKU',
                'Producto',
                'Tipo',
                'Cantidad',
                'Stock anterior',
                'Stock nuevo',
                'Motivo',
                'Usuario',
            ], ';');

            foreach ($movements as $movement) {
                fputcsv($handle, [
                    $movement->created_at?->format('Y-m-d H:i:s') ?? '',
                    $this->sanitizeCsvValue($movement->product?->sku ?? ''),
                    $this->sanitizeCsvValue($movement->product?->name ?? ''),
                    $movement->type->label(),
                    $movement->quantity,
                    $movement->previous_stock,
                    $movement->new_stock,
                    $this->sanitizeCsvValue($movement->reason ?? ''),
                    $this->sanitizeCsvValue($movement->user?->name ?? ''),
                ], ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
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

    /**
     * @return Builder<InventoryMovement>
     */
    private function filteredMovementsQuery(Request $request): Builder
    {
        $productId = $request->integer('product_id');
        $type = (string) $request->string('type');

        return InventoryMovement::query()
            ->with(['product', 'user'])
            ->when($productId > 0, fn (Builder $query) => $query->where('product_id', $productId))
            ->when(MovementType::tryFrom($type) !== null, fn (Builder $query) => $query->where('type', $type));
    }

    private function sanitizeCsvValue(string $value): string
    {
        if (preg_match('/^[=+\-@\t\r]/', $value) === 1) {
            return "'".$value;
        }

        return $value;
    }
}

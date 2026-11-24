<?php

namespace App\Http\Controllers;

use App\Enums\MovementType;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $categoryId = $request->integer('category_id');
        $stockFilter = (string) $request->string('stock_status');

        $products = Product::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId > 0, fn ($query) => $query->where('category_id', $categoryId))
            ->when($stockFilter === 'low', fn ($query) => $query->where('stock', '>', 0)->whereColumn('stock', '<=', 'min_stock'))
            ->when($stockFilter === 'out', fn ($query) => $query->where('stock', '<=', 0))
            ->when($stockFilter === 'ok', fn ($query) => $query->whereColumn('stock', '>', 'min_stock'))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => Category::query()->orderBy('name')->get(),
            'filters' => [
                'search' => $search,
                'category_id' => $categoryId,
                'stock_status' => $stockFilter,
            ],
        ]);
    }

    public function create(): View
    {
        return view('products.create', [
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = DB::transaction(function () use ($validated, $request): Product {
            $product = Product::query()->create($validated);

            if ($product->stock > 0) {
                InventoryMovement::query()->create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => MovementType::Entrada,
                    'quantity' => $product->stock,
                    'previous_stock' => 0,
                    'new_stock' => $product->stock,
                    'reason' => 'Stock inicial al crear producto',
                ]);
            }

            return $product;
        });

        return redirect()
            ->route('products.show', $product)
            ->with('status', "Producto \"{$product->name}\" creado correctamente.");
    }

    public function show(Product $product): View
    {
        $product->load('category');

        $movements = $product->movements()
            ->with('user')
            ->paginate(10);

        return view('products.show', [
            'product' => $product,
            'movements' => $movements,
            'movementTypes' => MovementType::cases(),
        ]);
    }

    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('products.show', $product)
            ->with('status', "Producto \"{$product->name}\" actualizado.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('status', "Producto \"{$name}\" eliminado.");
    }
}

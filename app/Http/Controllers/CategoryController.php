<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Category::class, 'name')],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Category::query()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('status', "Categoría \"{$validated['name']}\" creada.");
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Category::class, 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('status', "Categoría \"{$category->name}\" actualizada.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->withErrors([
                'category' => "No puedes eliminar \"{$category->name}\" porque tiene productos asociados.",
            ]);
        }

        $name = $category->name;
        $category->delete();

        return back()->with('status', "Categoría \"{$name}\" eliminada.");
    }
}

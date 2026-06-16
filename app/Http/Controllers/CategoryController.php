<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    // GET /categories
    public function index(): View
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    // GET /categories/create
    public function create(): View
    {
        return view('categories.create');
    }

    // POST /categories
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|min:3|max:100|unique:categories,name',
            'slug'        => 'required|string|unique:categories,slug',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori \"{$validated['name']}\" berhasil ditambahkan!");
    }

    // GET /categories/{category}
    public function show(Category $category): View
    {
        $category->load('products');
        return view('categories.show', compact('category'));
    }

    // GET /categories/{category}/edit
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    // PUT /categories/{category}
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|min:3|max:100|unique:categories,name,' . $category->id,
            'slug'        => 'required|string|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil diperbarui!");
    }

    // DELETE /categories/{category}
    public function destroy(Category $category): RedirectResponse
    {
        $activeProductCount = $category->products()
            ->where('status', 'active')
            ->count();

        if ($activeProductCount > 0) {
            return redirect()->route('categories.index')
                ->with('error', "Kategori \"{$category->name}\" tidak dapat dihapus karena masih memiliki {$activeProductCount} produk aktif!");
        }

        $nama = $category->name;
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', "Kategori \"{$nama}\" berhasil dihapus.");
    }
}

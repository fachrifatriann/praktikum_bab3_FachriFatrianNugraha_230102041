<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;


class ProductController extends Controller
{
    // GET /products — tampilkan semua produk
    public function index(Request $request): View
    {
        $search     = $request->input('search', '');
        $categoryId = $request->input('category_id');
        $sortBy     = $request->input('sort_by', 'created_at');
        $sortOrder  = $request->input('sort_order', 'desc');


        $products = Product::with('category')
            ->when($search,
                fn ($q) => $q->where('name', 'LIKE', "%{$search}%"))
            ->when($categoryId,
                fn ($q) => $q->where('category_id', $categoryId))
            ->orderBy($sortBy, $sortOrder)
            ->paginate(10)
            ->withQueryString();



        $categories = Category::orderBy('name')->get();
        return view('products.index', compact('products', 'categories', 'search'));
    }


    // GET /products/create — form tambah produk
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }


            // POST /products — simpan produk baru
        public function store(StoreProductRequest $request): RedirectResponse
        {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')
                    ->store('products', 'public');
            }

            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

            $product = Product::create($data);

            return redirect()->route('products.index')
                ->with('success', "Produk \"{$product->name}\" berhasil ditambahkan!");
}

    // GET /products/{product} — detail produk
    public function show(Product $product): View
    {
        $product->load('category');
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)->get();
        return view('products.show', compact('product', 'related'));
    }



    // GET /products/{product}/edit — form edit
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }


    // PUT /products/{product} — update produk
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => 'required|string|min:3|max:200',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);


        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }


        $product->update($validated);
        return redirect()->route('products.index')
            ->with('success', "Produk \"{$product->name}\" berhasil diperbarui!");
    }



    // DELETE /products/{product} — hapus produk
    public function destroy(Product $product): RedirectResponse
    {
        $nama = $product->name;
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', "Produk \"{$nama}\" berhasil dihapus.");
    }
}

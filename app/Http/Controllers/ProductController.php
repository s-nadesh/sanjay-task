<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Get the list of products.
     */
    public function index()
    {
        return Inertia::render('Products/Index', [
            'products' => Product::with('category.parentRecursive')
            ->latest()
            ->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new products.
     */
    public function create()
    {
        return Inertia::render('Products/Create', [
            'categories' => Category::with('childrenRecursive')
                ->whereNull('parent_id')
                ->where('status', 1)
                ->get(),
        ]);
    }

    /**
     * Store a newly created products details.
     */
    public function store(ProductRequest $request)
    {
        if ($request->hasFile('image')) {
            $request->image = $request->file('image')->store('products', 'public');
        }
        Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'image'       => $request->image,
            'description' => $request->description,
            'price'       => $request->price,
            'status'      => (bool) $request->status,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the products details.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => Category::with('childrenRecursive')
                ->whereNull('parent_id')
                ->where('status', 1)
                ->get(),
        ]);
    }

    /**
     * Update the products details.
     */
    public function update(ProductRequest $request, Product $product)
    {
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $request->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'image'       => $request->image ?? $product->image,
            'description' => $request->description,
            'price'       => $request->price,
            'status'      => (bool) $request->status,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete the sepecific product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderByDesc('id')->paginate(20);
        return view('admin.catalog', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        // AUTO-GENERATE SLUG FROM NAME
        $validated['slug'] = Str::slug($validated['name']);
        $count = Product::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        // HANDLE IMAGE UPLOAD
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = 'storage/' . $path;
        }
        unset($validated['image']);

        Product::create($validated);
        return redirect()->route('admin.catalog')->with('success', 'Product created');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        // AUTO-GENERATE SLUG FROM NAME IF NAME CHANGED
        if ($validated['name'] !== $product->name) {
            $validated['slug'] = Str::slug($validated['name']);
            $count = Product::where('slug', 'like', $validated['slug'] . '%')->where('id', '!=', $product->id)->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }
        }

        // HANDLE IMAGE UPLOAD
        if ($request->hasFile('image')) {
            // DELETE OLD IMAGE IF EXISTS
            if ($product->image_url && str_starts_with($product->image_url, 'storage/')) {
                $oldPath = str_replace('storage/', '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = 'storage/' . $path;
        }
        unset($validated['image']);

        $product->update($validated);
        return redirect()->route('admin.catalog')->with('success', 'Product updated');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.catalog')->with('success', 'Product deleted');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function indexByCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (! $category) {
            abort(404);
        }

        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $view = match ($slug) {
            'drinks' => 'customer.drinks',
            'pastries' => 'customer.pastries',
            default => 'customer.welcome',
        };

        return view($view, [
            'category' => $category,
            'products' => $products,
        ]);
    }

    public function show(Product $product)
    {
        return view('customer.product', compact('product'));
    }
}

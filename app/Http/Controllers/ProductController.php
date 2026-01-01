<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;

class ProductController extends Controller
{
    public function indexByCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->first();
        
        // GET SUBCATEGORIES WITH THEIR PRODUCTS
        $subcategories = $category 
            ? Subcategory::where('category_id', $category->id)
                ->with(['products' => function ($query) {
                    $query->where('is_active', true)->orderBy('name');
                }])
                ->get()
            : collect();

        // GET PRODUCTS WITHOUT SUBCATEGORY (DIRECTLY UNDER MAIN CATEGORY)
        $productsWithoutSubcategory = $category 
            ? Product::where('category_id', $category->id)
                ->whereNull('subcategory_id')
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
            : collect();

        $view = match ($slug) {
            'drinks' => 'customer.drinks',
            'pastries' => 'customer.pastries',
            default => 'customer.welcome',
        };

        return view($view, [
            'category' => $category,
            'subcategories' => $subcategories,
            'productsWithoutSubcategory' => $productsWithoutSubcategory,
        ]);
    }

    public function show(Product $product)
    {
        return view('customer.product', compact('product'));
    }
}

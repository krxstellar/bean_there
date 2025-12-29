<?php

namespace App\Http\Controllers;

use App\Models\Product;

class StaffProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderByDesc('id')->paginate(20);
        return view('staff.catalog', compact('products'));
    }
}

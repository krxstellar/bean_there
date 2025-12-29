<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('customer.cart');
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $id = (int) $validated['id'];
        $qty = (int) ($validated['quantity'] ?? 1);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $product = \App\Models\Product::find($id);
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => $qty,
                'price' => (float) $product->price,
                'image' => $product->image_url,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Added to cart!');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = session()->get('cart', []);
        if (isset($cart[$validated['id']])) {
            $cart[$validated['id']]['quantity'] = (int) $validated['quantity'];
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function removeFromCart(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
        ]);
        $cart = session()->get('cart', []);
        if (isset($cart[$validated['id']])) {
            unset($cart[$validated['id']]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Item removed.');
    }
}
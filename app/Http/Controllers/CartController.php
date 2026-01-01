<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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
            $product = Product::find($id);
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => $qty,
                'price' => (float) $product->price,
                'image' => $product->image_url,
            ];
        }

        session()->put('cart', $cart);

        // SYNC TO DATABASE IF USER IS LOGGED IN
        if (Auth::check()) {
            $this->syncCartToDatabase();
        }

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

            // SYNC TO DATABASE IF USER IS LOGGED IN
            if (Auth::check()) {
                $this->syncCartToDatabase();
            }

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

            // SYNC TO DATABASE IF USER IS LOGGED IN
            if (Auth::check()) {
                $this->syncCartToDatabase();
            }
        }
        return redirect()->back()->with('success', 'Item removed.');
    }

    // SYNC SESSION CART TO DATABASE FOR LOGGED-IN USER
    protected function syncCartToDatabase()
    {
        $userId = Auth::id();
        $cart = session()->get('cart', []);

        // CLEAR EXISTING CART ITEMS FOR THIS USER
        CartItem::where('user_id', $userId)->delete();

        // INSERT CURRENT CART ITEMS
        foreach ($cart as $productId => $item) {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
            ]);
        }
    }

    // LOAD CART FROM DATABASE TO SESSION (CALLED ON LOGIN)
    public static function loadCartFromDatabase($userId)
    {
        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();
        $sessionCart = session()->get('cart', []);

        foreach ($cartItems as $item) {
            $productId = $item->product_id;
            if (isset($sessionCart[$productId])) {
                // MERGE: ADD QUANTITIES
                $sessionCart[$productId]['quantity'] += $item->quantity;
            } else {
                $sessionCart[$productId] = [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => (float) $item->product->price,
                    'image' => $item->product->image_url,
                ];
            }
        }

        session()->put('cart', $sessionCart);

        // UPDATE DATABASE WITH MERGED CART
        CartItem::where('user_id', $userId)->delete();
        foreach ($sessionCart as $productId => $item) {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
            ]);
        }
    }
}
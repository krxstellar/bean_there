<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->orderBy('placed_at', 'desc')->get();
        return view('customer.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure the authenticated user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('customer.order-show', compact('order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping.full_name' => 'required|string|max:255',
            'shipping.phone' => 'required|string|max:32',
            'shipping.line1' => 'required|string|max:255',
            'shipping.line2' => 'nullable|string|max:255',
            'shipping.city' => 'required|string|max:120',
            'shipping.province' => 'required|string|max:120',
            'shipping.postal_code' => 'required|string|max:24',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        $itemsData = [];
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if (! $product || ! $product->is_active) {
                continue;
            }
            $qty = max(1, (int)($item['quantity'] ?? 1));
            $unitPrice = (float)($product->price);
            $total += $unitPrice * $qty;
            $itemsData[] = [
                'product_id' => $product->id,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
            ];
        }

        if ($total <= 0) {
            return redirect()->route('cart.index')->with('error', 'No valid items in cart.');
        }

        DB::transaction(function () use ($itemsData, $total, $request) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total' => $total,
                'currency' => 'PHP',
                'placed_at' => now(),
            ]);

            foreach ($itemsData as $data) {
                $data['order_id'] = $order->id;
                OrderItem::create($data);
            }

            $shipping = $request->input('shipping');
            Address::create([
                'order_id' => $order->id,
                'type' => 'shipping',
                'full_name' => $shipping['full_name'],
                'phone' => $shipping['phone'],
                'line1' => $shipping['line1'],
                'line2' => $shipping['line2'] ?? null,
                'city' => $shipping['city'],
                'province' => $shipping['province'],
                'postal_code' => $shipping['postal_code'],
            ]);
        });

        session()->forget('cart');
        return redirect()->route('welcome')->with('success', 'Order placed successfully!');
    }
}

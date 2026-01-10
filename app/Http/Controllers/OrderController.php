<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Product;
use App\Models\CartItem;
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
        // ENSURE THE AUTHENTICATED USER OWNS THIS ORDER
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('customer.order-show', compact('order'));
    }

    public function checkout(Request $request)
    {
        $selectedItems = $request->query('selected_items', '');
        $instructions = $request->query('instructions', '');
        
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Please select items to checkout.');
        }

        // STORE SELECTED ITEMS AND INSTRUCTIONS IN SESSION FOR THE STORE METHOD
        session()->put('checkout_items', $selectedItems);
        session()->put('checkout_instructions', $instructions);

        return view('customer.shipping');
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
            'apply_discount' => 'nullable|boolean',
            'discount_proof' => 'required_if:apply_discount,1|file|mimes:jpeg,png|max:5120',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // HANDLE DISCOUNT PROOF UPLOAD
        $proofPath = null;
        if ($request->hasFile('discount_proof')) {
            $proofPath = $request->file('discount_proof')->store('discount_proofs', 'public');
        }

        // GET SELECTED ITEMS FROM SESSION
        $selectedItems = session('checkout_items', '');
        $selectedIds = array_filter(explode(',', $selectedItems));

        if (empty($selectedIds)) {
            return redirect()->route('cart.index')->with('error', 'Please select items to checkout.');
        }

        $total = 0;
        $itemsData = [];
        $checkedOutIds = [];

        foreach ($cart as $id => $item) {
            // ONLY PROCESS SELECTED ITEMS
            if (!in_array($id, $selectedIds)) {
                continue;
            }

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
            $checkedOutIds[] = $id;
        }

        if ($total <= 0) {
            return redirect()->route('cart.index')->with('error', 'No valid items in cart.');
        }

        // GET INSTRUCTIONS FROM SESSION
        $instructions = session()->get('checkout_instructions', '');

        DB::transaction(function () use ($itemsData, $total, $request, $instructions, $proofPath) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total' => $total,
                'currency' => 'PHP',
                'instructions' => $instructions,
                'placed_at' => now(),
                'discount_proof' => $proofPath ?? null, // Store the discount proof path
                'discount_status' => isset($proofPath) ? 'pending' : 'none',
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

        // REMOVE ONLY CHECKED OUT ITEMS FROM CART, KEEP UNCHECKED ITEMS
        $remainingCart = array_diff_key($cart, array_flip($checkedOutIds));
        if (empty($remainingCart)) {
            session()->forget('cart');
        } else {
            session(['cart' => $remainingCart]);
        }

        // REMOVE PERSISTED CART ITEMS FOR LOGGED-IN USER THAT WERE CHECKED OUT
        if (auth()->check() && !empty($checkedOutIds)) {
            CartItem::where('user_id', auth()->id())->whereIn('product_id', $checkedOutIds)->delete();
        }

        session()->forget('checkout_items');
        session()->forget('checkout_instructions');
        return redirect()->route('welcome')->with('order_success', true);
    }
}

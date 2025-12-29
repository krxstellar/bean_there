<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;

class AdminOrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        $shipping = Address::where('order_id', $order->id)
            ->where('type', 'shipping')
            ->first();

        return view('admin.orders.show', [
            'order' => $order,
            'shipping' => $shipping,
        ]);
    }
}

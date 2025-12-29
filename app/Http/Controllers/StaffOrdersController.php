<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;

class StaffOrdersController extends Controller
{
    public const STATUSES = ['pending', 'processing', 'completed', 'cancelled'];

    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('staff.orders', [
            'orders' => $orders,
            'statuses' => self::STATUSES,
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        $shipping = Address::where('order_id', $order->id)
            ->where('type', 'shipping')
            ->first();

        return view('staff.order-show', [
            'order' => $order,
            'shipping' => $shipping,
            'statuses' => self::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Order status updated to ' . ucfirst($validated['status']));
    }
}

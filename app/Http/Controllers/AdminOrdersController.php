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
            'statuses' => Order::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Order::STATUSES),
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Order status updated to ' . ucfirst($validated['status']));
    }

    public function approveDiscount(Request $request, Order $order)
    {
        $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        $ok = $order->approveDiscount(auth()->user(), $request->input('note'));

        if (!$ok) {
            return back()->with('success', 'Unable to approve discount.');
        }

        return back()->with('success', 'Discount approved.');
    }

    public function rejectDiscount(Request $request, Order $order)
    {
        $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        $ok = $order->rejectDiscount(auth()->user(), $request->input('note'));

        if (!$ok) {
            return back()->with('success', 'Unable to reject discount.');
        }

        return back()->with('success', 'Discount rejected.');
    }
}

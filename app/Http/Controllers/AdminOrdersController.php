<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminOrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product', 'latestPayment'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'latestPayment']);
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

        $newStatus = $validated['status'];
        $oldStatus = $order->status;

        // Update status; when marking completed, create a Payment record (payments are the source of truth).
        $order->status = $newStatus;

        if ($newStatus === 'completed') {
            // create payment record if none exists for this order
            if (!$order->payments()->where('status', 'paid')->exists()) {
                $amount = ($order->discount_status ?? '') === 'approved'
                    ? $order->total_after_discount
                    : $order->total;

                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount' => $amount,
                    'method' => 'cod',
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                $payment->transaction_id = 'TXN-'.str_pad($payment->id, 6, '0', STR_PAD_LEFT);
                $payment->save();
            }
        }

        // If status was completed but is being changed away, remove payment record(s).
        if ($oldStatus === 'completed' && $newStatus !== 'completed') {
            $order->payments()->where('status', 'paid')->delete();
        }

        $order->save();

        return back()->with('success', 'Order status updated to ' . ucfirst($newStatus));
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

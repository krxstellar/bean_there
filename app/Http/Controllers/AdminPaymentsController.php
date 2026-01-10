<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentsController extends Controller
{
    public function index(Request $request)
    {
        // Use Payment model (only paid ones) to render transactions.
        $payments = Payment::with(['order.user'])
            ->where('status', 'paid')
            ->orderByDesc('paid_at')
            ->paginate(20);

        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $completedCount = Payment::where('status', 'paid')->count();

        return view('admin.payments', compact('payments', 'totalRevenue', 'completedCount'));
    }
}

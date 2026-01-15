<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPaymentsController extends Controller
{
    public function index(Request $request)
    {
        // Use Payment model to render transactions.
        $payments = Payment::with(['order.user'])
            ->where('status', 'paid')
            ->orderByDesc('paid_at')
            ->paginate(20);

        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $completedCount = Payment::where('status', 'paid')->count();

        return view('admin.payments', compact('payments', 'totalRevenue', 'completedCount'));
    }

    public function generateReceipt(Payment $payment)
    {
        $payment->load(['order.user', 'order.items']);

        // Require barryvdh/laravel-dompdf to generate PDF
        if (! class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            abort(500, 'PDF generator not installed. Run: composer require barryvdh/laravel-dompdf');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.receipt', compact('payment'));

        $path = 'receipts/' . $payment->id . '.pdf';
        Storage::put($path, $pdf->output());

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'receipt-'.$payment->id.'.pdf');
    }
}

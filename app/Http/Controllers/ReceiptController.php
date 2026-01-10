<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    public function download(Payment $payment)
    {
        $payment->load(['order.user', 'order.items']);

        $user = auth()->user();
        if (! $user) {
            abort(403, 'Not authenticated');
        }

        $isOwner = $payment->order && $payment->order->user && $payment->order->user->id === $user->id;
        $isAdmin = method_exists($user, 'hasRole') && $user->hasRole('admin');

        if (! ($isOwner || $isAdmin)) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

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

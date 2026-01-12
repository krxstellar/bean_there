<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $toProcess = Order::where('status', 'pending')->count();

        $inPreparation = OrderItem::whereHas('order', function ($q) {
            $q->where('status', 'processing');
        })->count();

        return view('staff.dashboard', [
            'toProcessCount' => $toProcess,
            'inPreparationCount' => $inPreparation,
        ]);
    }
}

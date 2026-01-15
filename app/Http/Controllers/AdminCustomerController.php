<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::withCount('orders')
            ->withSum('orders', 'total')
            ->where('email', 'not like', '%@beanthere.com')
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['admin', 'staff']);
            })
            ->orderByDesc('orders_sum_total')
            ->paginate(25);

        return view('admin.customers', compact('customers'));
    }
}

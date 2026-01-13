<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class StaffProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderByDesc('id')->paginate(20);
        return view('staff.catalog', compact('products'));
    }

    public function notifyLowStock(Request $request, Product $product)
    {
        $admin = User::role('admin')->first();
        if (!$admin) {
            Log::warning('LowStock notification attempted but no admin found.', ['product_id' => $product->id]);
            return redirect()->back()->with('error', 'Admin not found to notify.');
        }

        $note = $request->input('note');
        $reporter = $request->user();

        Notification::send($admin, new LowStockNotification($product, $reporter, $note));

        return redirect()->back()->with('success', 'Admin notified about low stock for ' . $product->name . '.');
    }
}

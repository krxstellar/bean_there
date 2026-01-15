<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminSubcategoryController;
use App\Http\Controllers\StaffProductController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminOrdersController;
use App\Http\Controllers\AdminPaymentsController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\StaffOrdersController;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;

// CUSTOMER ROUTES
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect('/test-admin');
        }
        if ($user->hasRole('staff')) {
            return redirect('/test-staff');
        }
    }

    return view('customer.welcome');
})->name('welcome');

Route::get('/about', function () {
    return view('customer.aboutus');
})->name('about');

Route::get('/faqs', function () {
    return view('customer.faqs');
})->name('faqs');

Route::get('/shipping-policy', function () {
    return view('customer.ShippingPolicy');
})->name('shipping.policy');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// MENU NAVIGATION ROUTES
Route::get('/drinks', function () {
    return app(ProductController::class)->indexByCategory('drinks');
})->name('menu.drinks');

Route::get('/pastries', function () {
    return app(ProductController::class)->indexByCategory('pastries');
})->name('menu.pastries');

// CART FUNCTIONALITY ROUTES
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');

// CHECKOUT FLOW ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/shipping', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    
    // ORDER TRACKING ROUTES
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Receipt download route for customers and admins
Route::get('/receipts/{payment}', [App\Http\Controllers\ReceiptController::class, 'download'])
    ->name('receipts.download')
    ->middleware('auth');

// PRODUCT DETAIL CUSTOMER
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// STAFF INTERFACE ROUTES
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/test-staff', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

    Route::get('/staff/orders', [StaffOrdersController::class, 'index'])->name('staff.orders');
    Route::get('/staff/orders/{order}', [StaffOrdersController::class, 'show'])->name('staff.orders.show');
    Route::patch('/staff/orders/{order}/status', [StaffOrdersController::class, 'updateStatus'])->name('staff.orders.updateStatus');

    // Allow staff to approve/reject discount requests
    Route::patch('/staff/orders/{order}/discount/approve', [StaffOrdersController::class, 'approveDiscount'])->name('staff.orders.discount.approve');
    Route::patch('/staff/orders/{order}/discount/reject', [StaffOrdersController::class, 'rejectDiscount'])->name('staff.orders.discount.reject');

    Route::get('/staff/catalog', [StaffProductController::class, 'index'])->name('staff.catalog');
    Route::post('/staff/products/{product}/notify-low-stock', [StaffProductController::class, 'notifyLowStock'])->name('staff.products.notifyLowStock');

    // STAFF SETTINGS
    Route::get('/staff/settings', function () {
        $settings = cache('admin.settings', [
            'email' => 'support@beanthere.com',
            'contact_number' => '0987 654 3210',
            'store_address' => 'Quezon City, Metro Manila',
            'hours_weekdays' => '8:00 AM - 7:00 PM',
            'hours_weekend' => '9:00 AM - 5:00 PM',
        ]);

        return view('staff.settings', compact('settings'));
    })->name('staff.settings');

    Route::post('/staff/settings', function (\Illuminate\Http\Request $request) {
        $data = $request->validate([
            'email' => 'required|email',
            'contact_number' => 'nullable|string|max:50',
            'store_address' => 'nullable|string|max:500',
            'hours_weekdays' => 'nullable|string|max:100',
            'hours_weekend' => 'nullable|string|max:100',
        ]);

        cache(['admin.settings' => $data], now()->addYears(5));

        return redirect()->route('staff.settings')->with('status', 'Settings updated.');
    })->name('staff.settings.update');
});

// ADMIN MANAGEMENT ROUTES 
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/test-admin', function () {
        $todaySales = Order::whereDate('placed_at', now()->toDateString())
            ->where('status', 'completed')
            ->sum('total');

        $todayRevenue = Payment::whereDate('paid_at', now()->toDateString())
            ->where('status', 'paid')
            ->sum('amount');

        $pendingOrders = Order::where('status', 'pending')->count();

        $outOfStock = Product::where('is_active', false)->count();

        return view('admin.dashboard', compact('todaySales', 'todayRevenue', 'pendingOrders', 'outOfStock'));
    });

    Route::get('/admin/orders', [AdminOrdersController::class, 'index'])->name('admin.orders');
    Route::get('/admin/orders/{order}', [AdminOrdersController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}/status', [AdminOrdersController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::patch('/admin/orders/{order}/discount/approve', [AdminOrdersController::class, 'approveDiscount'])->name('admin.orders.discount.approve');
    Route::patch('/admin/orders/{order}/discount/reject', [AdminOrdersController::class, 'rejectDiscount'])->name('admin.orders.discount.reject');

    Route::get('/admin/catalog', [AdminProductController::class, 'index'])->name('admin.catalog');

    Route::get('/admin/payments', [AdminPaymentsController::class, 'index'])->name('admin.payments');
    Route::get('/admin/payments/{payment}/receipt', [AdminPaymentsController::class, 'generateReceipt'])->name('admin.payments.receipt');

    Route::get('/admin/customers', [App\Http\Controllers\AdminCustomerController::class, 'index'])->name('admin.customers');

    Route::get('/admin/users', [AdminUsersController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [AdminUsersController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}', [AdminUsersController::class, 'show'])->name('admin.users.show');
    Route::patch('/admin/users/{user}', [AdminUsersController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/notifications', [App\Http\Controllers\AdminNotificationsController::class, 'index'])->name('admin.notifications');
    Route::patch('/admin/notifications/mark-all-read', [App\Http\Controllers\AdminNotificationsController::class, 'markAllRead'])->name('admin.notifications.markAllRead');
    Route::patch('/admin/notifications/{id}/mark-read', [App\Http\Controllers\AdminNotificationsController::class, 'markRead'])->name('admin.notifications.markRead');
    Route::get('/admin/notifications/{id}/view', [App\Http\Controllers\AdminNotificationsController::class, 'viewAndRedirect'])->name('admin.notifications.viewAndRedirect');

    Route::get('/admin/settings', function () {
        $settings = cache('admin.settings', [
            'email' => 'support@beanthere.com',
            'contact_number' => '0987 654 3210',
            'store_address' => 'Quezon City, Metro Manila',
            'hours_weekdays' => '8:00 AM - 7:00 PM',
            'hours_weekend' => '9:00 AM - 5:00 PM',
        ]);

        return view('admin.settings', compact('settings'));
    })->name('admin.settings');

    Route::post('/admin/settings', function (Request $request) {
        $data = $request->validate([
            'email' => 'required|email',
            'contact_number' => 'nullable|string|max:50',
            'store_address' => 'nullable|string|max:500',
            'hours_weekdays' => 'nullable|string|max:100',
            'hours_weekend' => 'nullable|string|max:100',
        ]);

        cache(['admin.settings' => $data], now()->addYears(5));

        return redirect()->route('admin.settings')->with('status', 'Settings updated.');
    })->name('admin.settings.update');

    Route::post('/admin/settings/change-password', function (Request $request) {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->route('admin.settings')
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('admin.settings')->with('status', 'Password changed.');
    })->name('admin.settings.change_password');

    // ADMIN PRODUCTS CRUD 
    Route::prefix('admin')->group(function () {
        Route::resource('products', AdminProductController::class)->except(['index'])->names([
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
            'show' => 'admin.products.show',
        ]);

        // ADMIN SUBCATEGORIES CRUD
        Route::resource('subcategories', AdminSubcategoryController::class)->except(['show'])->names([
            'index' => 'admin.subcategories.index',
            'create' => 'admin.subcategories.create',
            'store' => 'admin.subcategories.store',
            'edit' => 'admin.subcategories.edit',
            'update' => 'admin.subcategories.update',
            'destroy' => 'admin.subcategories.destroy',
        ]);
    });
});
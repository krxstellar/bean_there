<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\StaffProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminOrdersController;
use App\Http\Controllers\StaffOrdersController;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Product;

// CUSTOMER ROUTES
Route::get('/', function () {
    return view('customer.welcome');
})->name('welcome');

Route::get('/about', function () {
    return view('customer.aboutus');
})->name('about');

Route::get('/faqs', function () {
    return view('customer.faqs');
})->name('faqs');

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

// CHECKOUT FLOW ROUTES (PROTECTED - REQUIRES LOGIN)
Route::middleware('auth')->group(function () {
    Route::get('/shipping', function () {
        return view('customer.shipping');
    })->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    
    // ORDER TRACKING ROUTES
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// PRODUCT DETAIL (CUSTOMER)
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// STAFF INTERFACE ROUTES (PROTECTED BY STAFF ROLE)
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/test-staff', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');

    Route::get('/staff/orders', [StaffOrdersController::class, 'index'])->name('staff.orders');
    Route::get('/staff/orders/{order}', [StaffOrdersController::class, 'show'])->name('staff.orders.show');
    Route::patch('/staff/orders/{order}/status', [StaffOrdersController::class, 'updateStatus'])->name('staff.orders.updateStatus');

    Route::get('/staff/catalog', [StaffProductController::class, 'index'])->name('staff.catalog');
});

// ADMIN MANAGEMENT ROUTES (PROTECTED BY ADMIN ROLE)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/test-admin', function () {
        return view('admin.dashboard');
    });

    Route::get('/admin/orders', [AdminOrdersController::class, 'index'])->name('admin.orders');
    Route::get('/admin/orders/{order}', [AdminOrdersController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}/status', [AdminOrdersController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    Route::get('/admin/catalog', [AdminProductController::class, 'index'])->name('admin.catalog');

    Route::get('/admin/payments', function () {
        return view('admin.payments');
    })->name('admin.payments');

    Route::get('/admin/customers', function () {
        return view('admin.customers');
    })->name('admin.customers');

    Route::get('/admin/users', function () {
        return view('admin.users');
    })->name('admin.users');

    Route::get('/admin/notifications', function () {
        return view('admin.notifications');
    })->name('admin.notifications');

    Route::get('/admin/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');

    // ADMIN PRODUCTS CRUD (INDEX HANDLED BY ADMIN.CATALOG ROUTE ABOVE)
    Route::prefix('admin')->group(function () {
        Route::resource('products', AdminProductController::class)->except(['index'])->names([
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
            'show' => 'admin.products.show',
        ]);
    });
});
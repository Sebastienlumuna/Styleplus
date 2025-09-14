<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/products/{product}', [PageController::class, 'show'])->name('products.show');
Route::get('/product-image/{product}', [PageController::class, 'getImage'])->name('product.image');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Dashboard routes (protégées par authentification)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/client', [App\Http\Controllers\Dashboard\ClientDashboardController::class, 'index'])->name('dashboard.client');
    Route::get('/dashboard/admin', [App\Http\Controllers\Dashboard\AdminDashboardController::class, 'index'])->name('dashboard.admin');
});

Route::resource('order', OrderController::class)->names('order');

Route::middleware(['auth'])->group(function () {
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::get('/order/{order}/payment', [PaymentController::class, 'index'])->name('payment');
    Route::post('/order/{order}/payment', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{payment}/invoice', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/payment/{payment}/invoice/pdf', [InvoiceController::class, 'download'])->name('invoice.pdf');

});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->names('admin.products');
    
});

// Auth
require __DIR__.'/auth.php';

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
    Route::get('/dashboard/livreur', [App\Http\Controllers\Dashboard\LivreurDashboardController::class, 'index'])->name('dashboard.livreur');
    Route::get('/profil', [\App\Http\Controllers\ProfilController::class, 'edit'])->name('profil.edit');
    Route::post('/profil', [\App\Http\Controllers\ProfilController::class, 'update'])->name('profil.update');
    
    // Livraisons client
    Route::get('/my-deliveries', [\App\Http\Controllers\DeliveryController::class, 'myDeliveries'])->name('my.deliveries');
    Route::get('/deliveries/{delivery}', [\App\Http\Controllers\DeliveryController::class, 'show'])->name('deliveries.show');
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
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'destroy'])->names('admin.orders');
    Route::get('orders/export/pdf', [\App\Http\Controllers\Admin\OrderController::class, 'exportPdf'])->name('admin.orders.export.pdf');
    Route::get('products/report/pdf', [\App\Http\Controllers\Admin\ProductController::class, 'exportPdf'])->name('admin.products.report.pdf');
    Route::get('statistics', [\App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('admin.statistics');
});

// Routes livreur
Route::middleware(['auth', 'role:livreur'])->prefix('livreur')->group(function () {
    Route::post('/deliveries/{delivery}/assign', [\App\Http\Controllers\Dashboard\LivreurDashboardController::class, 'assign'])->name('livreur.assign');
    Route::post('/deliveries/{delivery}/start', [\App\Http\Controllers\Dashboard\LivreurDashboardController::class, 'startDelivery'])->name('livreur.start');
    Route::post('/deliveries/{delivery}/complete', [\App\Http\Controllers\Dashboard\LivreurDashboardController::class, 'completeDelivery'])->name('livreur.complete');
    Route::get('/history', [\App\Http\Controllers\Dashboard\LivreurDashboardController::class, 'history'])->name('livreur.history');
});

// Auth
require __DIR__.'/auth.php';

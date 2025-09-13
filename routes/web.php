<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
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

// Auth
require __DIR__.'/auth.php';

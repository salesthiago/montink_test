<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('products');
});

Route::resource('products', ProductController::class);

Route::prefix('/cart')->group(function () {
    Route::post('/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::get('/remove/{key}', [CartController::class, 'destroy'])->name('cart.destroy');
});


Route::post('/search-by-cep', [CartController::class, 'searchCEP'])->name('findAddress');
Route::post('/delivery-simulator', [CartController::class, 'deliverySimulator'])->name('deliverySimulator');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

Route::resource('coupons', CouponController::class)->except(['show']);
Route::post('/coupons/validate', [CouponController::class, 'validateCoupon'])->name('coupons.validate');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');


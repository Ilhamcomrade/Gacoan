<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [MenuController::class, 'index']);
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/order', [OrderController::class, 'submit'])->name('order.submit');
Route::get('/order/summary/{id}', [OrderController::class, 'summary'])->name('order.summary');
Route::delete('/order/cancel/{id}', [OrderController::class, 'cancel'])->name('order.cancel');


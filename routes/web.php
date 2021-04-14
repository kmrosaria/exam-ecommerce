<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MyController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MyController::class,'getAllProducts']);
Route::get('/product/{uuid}', [MyController::class, 'getProductById']);


Route::prefix('cart')->group(function () {
    Route::get('/{id}', [MyController::class, 'getCartByUser']);
});

Route::get('checkout/{id}/', [MyController::class, 'checkoutCart']);
Route::get('orders/{id}/', [MyController::class, 'getOrdersByUser']);


Route::prefix('admin')->group(function () {
    Route::get('/orders', [AdminController::class, 'getAllOrders']);
});

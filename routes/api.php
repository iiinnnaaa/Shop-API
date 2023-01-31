<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Product\CartController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('verification/resend', [VerificationController::class, 'resend'])->name('resend');

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'show'])->name('account');
        Route::put('update', [UserController::class, 'update'])->name('update');
        Route::delete('delete', [UserController::class, 'delete'])->name('delete');

        Route::get('cart', [CartController::class, 'listItems']);
        Route::delete('cart/empty', [CartController::class, 'emptyCart']);
        Route::get('cart/purchase', [CartController::class, 'purchaseItems']);
    });

    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::get('{product}',[ProductController::class, 'show']);
        Route::put('{product}',[ProductController::class, 'update']);
        Route::delete('{product}',[ProductController::class, 'destroy']);

        Route::get('{product}/add',[CartController::class, 'addToCart']);
        Route::delete('{product}/remove',[CartController::class, 'removeFromCart']);
    });

//    Route::get('cart/{product}', [CartController::class, 'listItems']);
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::post('verification/{code}', [VerificationController::class, 'verify'])->name('verification');

Route::get('products', [ProductController::class, 'index']);


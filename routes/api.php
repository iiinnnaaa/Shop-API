<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Product\CartController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Product\StripePaymentController;
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
        Route::get('{id}', [UserController::class, 'show'])->name('account');
        Route::put('{id}/update', [UserController::class, 'update'])->name('update');
        Route::delete('{id}/delete', [UserController::class, 'delete'])->name('delete');

        Route::get('{id}/cart', [CartController::class, 'listItems']);
        Route::delete('{id}/cart/empty', [CartController::class, 'emptyCart']);
        Route::get('{id}/cart/purchase', [CartController::class, 'purchaseItems']);
    });

    Route::prefix('products')->group(function () {
        Route::put('{product}',[ProductController::class, 'update']);
        Route::delete('{product}',[ProductController::class, 'destroy']);
        Route::get('{product}/add',[CartController::class, 'addToCart']);
        Route::delete('{product}/remove',[CartController::class, 'removeFromCart']);
    });

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('stripe',[StripePaymentController::class, 'stripePost'])->name('stripe.post');
});

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('verification', [VerificationController::class, 'verify'])->name('verification');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('{product}', [ProductController::class, 'show']);
});


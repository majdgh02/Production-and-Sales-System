<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::post('/login', [AuthController::class, 'login']);

// المسارات المحمية (تتطلب مصادقة باستخدام Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changepass']);
    Route::get('/get-info', [UserController::class, 'get_my_info']);


});

Route::middleware('auth:sanctum', 'role:admin')->prefix('admin')->group(function () {
    //Accounts manegment
    Route::get('/get-account', [UserController::class, 'showAccounts']);
    Route::post('/create-account', [UserController::class, 'create_account']);
    Route::put('/edit-account', [UserController::class, 'edit_account']);
    Route::delete('/delete-account/{user}', [UserController::class, 'deleteUser']);

    //Products manegment
    Route::post('/create-product', [ProductController::class, 'create_product']);
    Route::post('/edit-product/{product}', [ProductController::class, 'edit_product']);
    Route::get('/get-product/{product}', [ProductController::class, 'get_product']);
    Route::get('/products/search', [ProductController::class, 'products_search']); //search for the admin (include the off products)
    Route::post('/product/frees-or-unfrees/{product}', [ProductController::class, 'product_frees_or_unfrees']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('/login', [AuthController::class, 'login']);

// المسارات المحمية (تتطلب مصادقة باستخدام Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/change-password', [AuthController::class, 'changepass']);
    Route::get('/user/get-info', [UserController::class, 'get_my_info']);
    Route::get('/users', [UserController::class, 'showAccounts']);
    Route::post('/user/create-account', [UserController::class, 'create_account']);
    Route::put('/user/edit-account', [UserController::class, 'edit_account']);
    Route::delete('/user/delete-account/{user}', [UserController::class, 'deleteUser']);
});

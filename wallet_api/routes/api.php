<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::post('/register',[AuthController::class,'store']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('wallets', WalletController::class);
    Route::post('/wallets/{id}/withdraw',[TransactionController::class,'withdraw']);
    Route::post('/wallets/{id}/deposit',[TransactionController::class,'deposit']);
    Route::post('/wallets/{id}/transfer',[TransactionController::class,'transfer']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/profile',[AuthController::class,'profile']);
    });
Route::get('/cheklogin',[AuthController::class,'index'])->name('login');


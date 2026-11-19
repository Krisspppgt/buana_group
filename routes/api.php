<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::match(['get', 'post'], '/payment/midtrans-webhook', [PaymentController::class, 'webhook'])
    ->name('api.payment.midtrans.webhook');

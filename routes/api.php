<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Protected API routes
    Route::middleware('subscription')->group(function () {
        Route::get('/premium-tips', function () {
            // Return premium tips
        });
    });
});

// Public API routes
Route::get('/public-tips', function () {
    // Return public tips
});

// Paystack webhook
Route::post('/webhooks/paystack', function (Request $request) {
    // Handle Paystack webhook
})->middleware('payment.webhook');

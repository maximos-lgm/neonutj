<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Healthcheck & Información de la API
Route::get('/', function () {
    return response()->json([
        'message' => 'API REST Neon PostgreSQL en línea',
        'status' => 'connected',
        'database' => 'Neon Cloud PostgreSQL',
        'version' => '1.0.0',
        'endpoints' => [
            'users' => '/api/users',
            'products' => '/api/products',
            'orders' => '/api/orders',
        ]
    ]);
});

// Rutas de la API (CRUDs)
Route::apiResource('users', UserController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('orders', OrderController::class);

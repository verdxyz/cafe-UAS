<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Prefix: /api
| All routes are automatically prefixed with /api by Laravel.
|
| Throttling:
|   - All API routes: 100 requests/hour (applied via bootstrap/app.php)
|   - Auth routes (login/register): 10 requests/minute (throttle:auth)
|
*/

// ─── Auth (Public) ───────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::middleware('throttle:auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::middleware('jwt.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// ─── Menu (Public read, Admin write) ─────────────────────────────────────────
Route::middleware('throttle:menu')->group(function () {
    Route::get('/menu', [MenuController::class, 'index']);
    Route::get('/menu/{menu}', [MenuController::class, 'show']);
});

Route::middleware(['jwt.auth', 'role:admin'])->group(function () {
    Route::post('/menu', [MenuController::class, 'store']);
    Route::put('/menu/{menu}', [MenuController::class, 'update']);
    Route::delete('/menu/{menu}', [MenuController::class, 'destroy']);
});

// ─── Orders (Authenticated) ─────────────────────────────────────────────────
Route::middleware('jwt.auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/report', [OrderController::class, 'report']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);

    Route::middleware('role:admin')->group(function () {
        Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
    });
});

// ─── Reservations (Authenticated) ───────────────────────────────────────────
Route::middleware('jwt.auth')->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update']);

    Route::middleware('role:admin')->group(function () {
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);
    });
});

// ─── Reviews (Public read, Authenticated write) ─────────────────────────────
Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{review}', [ReviewController::class, 'show']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);

    Route::middleware('role:admin')->group(function () {
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    });
});

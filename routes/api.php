<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemPesananController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PesananController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('pembeli')->group(function () {
    Route::get('/', [PembeliController::class, 'index']);
    Route::get('/{id}', [PembeliController::class, 'show']);
    Route::post('/', [PembeliController::class, 'store']);
    Route::put('/{id}', [PembeliController::class, 'update']);
    Route::delete('/{id}', [PembeliController::class, 'destroy']);
});

Route::prefix('item-pesanan')->group(function () {
    Route::get('/', [ItemPesananController::class, 'index']);
    Route::get('/{id}', [ItemPesananController::class, 'show']);
    Route::post('/', [ItemPesananController::class, 'store']);
    Route::put('/{id}', [ItemPesananController::class, 'update']);
    Route::delete('/{id}', [ItemPesananController::class, 'destroy']);
});

Route::prefix('item')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::get('/{id}', [ItemController::class, 'show']);
    Route::post('/', [ItemController::class, 'store']);
    Route::put('/{id}', [ItemController::class, 'update']);
    Route::delete('/{id}', [ItemController::class, 'destroy']);
});

Route::prefix('pesanan')->group(function () {
    Route::get('/', [PesananController::class, 'index']);
    Route::get('/{id}', [PesananController::class, 'show']);
    Route::post('/', [PesananController::class, 'store']);
    Route::put('/{id}', [PesananController::class, 'update']);
    Route::delete('/{id}', [PesananController::class, 'destroy']);
});

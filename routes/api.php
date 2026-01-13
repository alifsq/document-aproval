<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);


Route::middleware(['auth:api-token', 'tenant.active'])->group(function () {
    Route::get('/document',[DocumentController::class, 'index']);

});
Route::post('auth/logout', [AuthController::class, 'logout']);

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'tenant.isactive'])->group(function () {
    Route::get('/documents',[DocumentController::class, 'index']);

});
Route::post('auth/logout', [AuthController::class, 'logout']);

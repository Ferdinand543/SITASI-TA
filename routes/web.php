<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/register-admin', [AuthController::class, 'showRegister']);
Route::post('/register-admin', [AuthController::class, 'register']);

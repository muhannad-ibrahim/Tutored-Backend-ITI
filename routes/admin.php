<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('me', [AuthController::class, 'me']);

Route::get('/admins', [AuthController::class,'index']);
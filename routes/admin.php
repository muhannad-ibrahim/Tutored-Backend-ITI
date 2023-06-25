<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;


Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('me', [AuthController::class, 'me']);

Route::get('/admins', [AuthController::class,'index']);



Route::post('/categories', [CategoryController::class, 'store'])->middleware(['cors']);
Route::post('/categories/{id}',[CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'delete']);

Route::delete('/courses/{id}', [CourseController::class, 'destroy']);



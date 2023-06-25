<?php

use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//login and register Admin
Route::post('login', [AuthController::class, 'login']);





//get categories
Route::get('/categories', [CategoryController::class, 'index']);
//get categories by id
Route::get('/categories/{id}', [CategoryController::class, 'show']);



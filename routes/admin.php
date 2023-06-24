<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactUsController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application.
*/

Route::get('/contact_us', [ContactUsController::class, 'index']);
Route::get('/contact_us/{id}', [ContactUsController::class, 'show']);
Route::delete('/contact_us/{id}', [ContactUsController::class, 'destroy']);
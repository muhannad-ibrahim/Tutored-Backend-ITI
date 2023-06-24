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

Route::get('/Contact_us', [ContactUsController::class, 'index']);
Route::get('/Contact_us/{id}', [ContactUsController::class, 'show']);
Route::delete('/Contact_us/{id}', [ContactUsController::class, 'destroy']);
<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/students',[StudentController::class, 'index']);
Route::get('/students/{id}',[StudentController::class, 'show']);

<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\payment;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::get('/students',[StudentController::class, 'index']);
Route::get('/students/{id}',[StudentController::class, 'show']);

Route::post('/courses/{course}/feedback',  [FeedbackController::class, 'store']);
Route::put('/courses/{course}/feedback/{feedback}', [FeedbackController::class, 'update']);

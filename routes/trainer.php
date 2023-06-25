<?php

use App\Http\Controllers\TrainerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;


Route::get('/trainers', [TrainerController::class, 'index']);
Route::get('/trainers/{id}', [TrainerController::class, 'show']);


Route::post('/courses', [CourseController::class, 'store']);
Route::post('/courses/{id}', [CourseController::class, 'update']);
Route::patch('/courses/{id}', [CourseController::class, 'update']);

//show student by Course id
Route::get('/student/showStudent/{id}', [CourseController::class, 'showStudent']);

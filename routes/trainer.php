<?php

use App\Http\Controllers\TrainerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseContentController;



Route::get('/trainers', [TrainerController::class, 'index']);
Route::get('/trainers/{id}', [TrainerController::class, 'show']);


Route::post('/courses', [CourseController::class, 'store']);
Route::post('/courses/{id}', [CourseController::class, 'update']);
Route::patch('/courses/{id}', [CourseController::class, 'update']);

//show student by Course id
Route::get('/student/showStudent/{id}', [CourseController::class, 'showStudent']);


Route::post('/Course_content', [CourseContentController::class, 'store']);
Route::put('/Course_content/{id}', [CourseContentController::class, 'update']);
Route::patch('/Course_content/{id}', [CourseContentController::class, 'update']);
Route::delete('/Course_content/{id}', [CourseContentController::class, 'destroy']);

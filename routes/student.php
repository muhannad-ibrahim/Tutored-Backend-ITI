<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\TrainerController;


//show Course content by Course id

Route::get('/course_content', [CourseContentController::class, 'index']);
Route::get('/course_content/{id}', [CourseContentController::class, 'show']);

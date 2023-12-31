<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ExamController;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application.
*/

Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('me', [AuthController::class, 'me']);

Route::get('/admins', [AuthController::class,'index']);

Route::post('/categories', [CategoryController::class, 'store'])->middleware(['cors']);
Route::post('/categories/{id}',[CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'delete']);

Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

Route::get('/contact_us', [ContactUsController::class, 'index']);
Route::get('/contact_us/{id}', [ContactUsController::class, 'show']);
Route::delete('/contact_us/{id}', [ContactUsController::class, 'destroy']);

Route::get('/courses/feedbacks', [FeedbackController::class, 'index']);

Route::get('/exams', [ExamController::class, 'index']);


Route::delete('/trainers/{id}',[TrainerController::class, 'destroy']);
Route::delete('/students/{id}',[StudentController::class, 'destroy']);


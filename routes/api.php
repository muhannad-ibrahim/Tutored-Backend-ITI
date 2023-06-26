<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactUsController;

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

//login Admin
Route::post('login', [AuthController::class, 'login']);

//get categories
Route::get('/categories', [CategoryController::class, 'index']);
//get categories by id
Route::get('/categories/{id}', [CategoryController::class, 'show']);
//get categories count
Route::get('/categories/count',[CategoryController::class,'getCount']);
// get courses of specific category
Route::get('/categories/courses/{id}', [CategoryController::class, 'showCategoryCourses']);
// routes for student
Route::post('/student/register',[StudentController::class,'register']);
Route::post('/student/login', [StudentController::class, 'login']);

Route::middleware('checkStudent:students')->group(function () {
    Route::post('/student/me', [StudentController::class, 'me']);
    Route::post('/student/logout', [StudentController::class, 'logout']);
    Route::post('/student/hello', [StudentController::class, 'sayHello']);
    Route::post('/students/{id}',[StudentController::class, 'update']);
    Route::post('/courses/{course}/feedback',  [FeedbackController::class, 'store']);
    Route::put('/courses/{course}/feedback/{feedback}', [FeedbackController::class, 'update']);
});

Route::get('/students/count',[StudentController::class,'getCount']);

// routes for trainer
Route::post('/trainers/register', [TrainerController::class, 'register']);
Route::post('/trainers/login', [TrainerController::class, 'login']);

Route::middleware('checkTrainer:trainers')->group(function () {
    Route::post('/trainers/me', [TrainerController::class, 'me']);
    Route::post('/trainers/logout', [TrainerController::class, 'logout']);
    Route::post('/trainers/hello', [TrainerController::class, 'sayHello']);
    Route::post('/trainers/{id}',[TrainerController::class, 'update']);

    Route::post('/exams', [ExamController::class, 'store']);
    Route::put('/exams/{id}', [ExamController::class, 'update']);
});

Route::get('/trainers/count',[TrainerController::class,'getCount']);

//get courses count
Route::get('/courses/count',[CourseController::class,'getCount']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::get('/student/studentCount/{id}', [CourseController::class, 'studentCount']);
Route::post('/studentcourseenroll', [CourseController::class, 'course_student_enroll']);

Route::middleware('studentOrAdmin:students,api')->group(function () {
    Route::delete('/courses/{course}/feedback/{feedback}', [FeedbackController::class, 'destroy']);
});

Route::post('/contact_us', [ContactUsController::class, 'store']);

Route::get('/courses/{course}/feedback', [FeedbackController::class, 'show']);

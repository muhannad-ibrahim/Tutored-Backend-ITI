<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\TrainerController;

//show courses by student id
Route::get('/student/showCourses/{id}', [CourseController::class, 'showCourses']);
//enroll
Route::post('/student/storeCourse',[CourseController::class,'Enrollment']);
//show Course content by Course id
Route::get('/Course_content/show/{c_id}', [CourseController::class, 'showvideo']);

Route::get('/Course_content', [CourseContentController::class, 'index']);
Route::get('/Course_content/{id}', [CourseContentController::class, 'show']);


Route::get('/student/courses/{id}',[StudentController::class,'getCoursesByStudentId']);
Route::get('/trainer/courses/{id}',[TrainerController::class,'getCoursesByTrainerId']);

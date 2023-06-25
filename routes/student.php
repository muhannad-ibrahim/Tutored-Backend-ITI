<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;


Route::get('/students',[StudentController::class, 'index']);
Route::get('/students/{id}',[StudentController::class, 'show']);



//show courses by student id
Route::get('/student/showCourses/{id}', [CourseController::class, 'showCourses']);
//enrolle
Route::post('/student/storeCourse',[CourseController::class,'Enrollment']);
//show Course content by Course id
Route::get('/Course_content/show/{c_id}', [CourseController::class, 'showvideo']);

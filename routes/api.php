<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


// routes for student
Route::post('/student/register',[StudentController::class,'register']);
Route::post('/student/login', [StudentController::class, 'login']);

Route::middleware('checkStudent:students')->group(function () {
    Route::post('/student/me', [StudentController::class, 'me']);
    Route::post('/student/logout', [StudentController::class, 'logout']);
    Route::post('/student/hello', [StudentController::class, 'sayHello']);
    Route::post('/students/{id}',[StudentController::class, 'update']);
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
});

Route::get('/trainers/count',[TrainerController::class,'getCount']);

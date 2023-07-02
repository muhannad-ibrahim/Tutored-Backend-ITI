<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ZoomClassesController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\ChatMessageController;
use App\Events\ChatMessageSent;
use App\Models\Trainer;
use App\Http\Controllers\PrivateChatController;
use App\Http\Controllers\ChatController;


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

//get categories count
//get categories
Route::get('/categories', [CategoryController::class, 'index']);
//get categories count
Route::get('/categories/count',[CategoryController::class,'getCount']);
//get categories by id
Route::get('/categories/{id}', [CategoryController::class, 'show'])->where('id', '[0-9]+');
// get courses of specific category
Route::get('/categories/courses/{id}', [CategoryController::class, 'showCategoryCourses']);

// routes for student
Route::get('/students',[StudentController::class, 'index']);
Route::get('/students/{id}',[StudentController::class, 'show']);
Route::post('/student/register',[StudentController::class,'register']);
Route::post('/student/login', [StudentController::class, 'login']);

Route::middleware('checkStudent:students')->group(function () {
    Route::post('/student/me', [StudentController::class, 'me']);
    Route::post('/student/logout', [StudentController::class, 'logout']);
    Route::post('/student/hello', [StudentController::class, 'sayHello']);
    Route::post('/students/{id}',[StudentController::class, 'update']);
    Route::post('/courses/{course}/feedback',  [FeedbackController::class, 'store']);
    Route::patch('/courses/{courseId}/feedback/{feedbackId}', [FeedbackController::class, 'update']);
    Route::put('/courses/{course}/feedback/{feedback}', [FeedbackController::class, 'update']);
    Route::get('courses/{courseId}/exams/{examId}', [ExamController::class, 'showExam']);
    Route::post('/courses/{courseId}/exams/{examId}/degree', [ExamController::class, 'storeExamDegree']);
    Route::get('/courses/{courseId}/exams/{examId}/degree', [ExamController::class, 'getExamDegree']);
    Route::put('/courses/{courseId}/progress', [CourseController::class, 'updateProgress']);
    Route::get('/courses/{course}/progress', [CourseController::class, 'getProgress']);
    Route::post('/courses/{course}/completion', [CourseController::class, 'completeCourse']);


});

Route::get('/students/count',[StudentController::class,'getCount']);

// routes for trainer
Route::get('/trainers', [TrainerController::class, 'index']);
Route::get('/trainers/{id}', [TrainerController::class, 'show']);
Route::post('/trainers/register', [TrainerController::class, 'register']);
Route::post('/trainers/login', [TrainerController::class, 'login']);

Route::middleware('checkTrainer:trainers')->group(function () {
    Route::post('/trainers/me', [TrainerController::class, 'me']);
    Route::post('/trainers/logout', [TrainerController::class, 'logout']);
    Route::post('/trainers/hello', [TrainerController::class, 'sayHello']);
    Route::post('/trainers/{id}',[TrainerController::class, 'update']);

    Route::post('/exams', [ExamController::class, 'store']);
    Route::put('/exams/{id}', [ExamController::class, 'update']);
    Route::delete('/exams/{id}', [ExamController::class, 'destroy']);

    Route::post('/exams/{examId}/questions', [QuestionController::class, 'store']);
    Route::put('questions/{questionId}', [QuestionController::class, 'update']);
    Route::delete('questions/{questionId}', [QuestionController::class, 'destroy']);


});

Route::get('/trainers/count',[TrainerController::class,'getCount']);

//get courses count
Route::get('/courses/count',[CourseController::class,'getCount']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show'])->where('id', '[0-9]+');
Route::get('/student/studentCount/{id}', [CourseController::class, 'studentCount']);
Route::post('/studentcourseenroll', [CourseController::class, 'course_student_enroll']);

Route::middleware('adminOrStudent:students,api')->group(function () {
    Route::delete('/courses/{course}/feedback/{feedback}', [FeedbackController::class, 'destroy']);
    Route::get('/courses/{courseId}/exams', [ExamController::class, 'getAllCourseExams']);
    Route::get('/courses/{courseId}/exams-with-questions', [ExamController::class, 'getAllCourseExamsWithQuestions']);
    Route::get('/exams/{examId}/questions', [ExamController::class, 'getAllExamQuestions']);
});

Route::post('/contact_us', [ContactUsController::class, 'store']);

Route::get('/courses/{course}/feedback', [FeedbackController::class, 'show']);


Route::post('payment-intent', [PaymentController::class,'CreatePayIntent']);
Route::post('store-intent', [PaymentController::class,'storeStripePayment']);
Route::get('/courses/{course}/feedbacks', [FeedbackController::class, 'show']);


// routes for zoom classes
Route::get('/zoom_classes', [ZoomClassesController::class, 'index']);
Route::post('/zoom_classes', [ZoomClassesController::class, 'store']);
Route::delete('/zoom_classes/{id}', [ZoomClassesController::class, 'destroy']);

// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');


Route::post('chat/send-message', [ChatMessageController::class, 'sendMessage']);



// Route::post('chat/message', function (Request $request) {
//     $message = $request->input('message');
//     $studentId = $request->input('student_id');
//     $trainerId = $request->input('trainer_id');

//     // Trigger the ChatMessageSent event
//     event(new ChatMessageSent($message, $studentId, $trainerId));

//     return response()->json(['success' => true]);
// });



Route::post('chat/send', [ChatController::class, 'sendMessage']);


Route::post('/chat/message', function (Request $request) {
    $message = $request->input('message');
    $studentId = $request->input('student_id');
    $trainerId = $request->input('trainer_id');

    // Trigger the ChatMessageSent event
    event(new ChatMessageSent($message, $studentId, $trainerId));

    return response()->json(['success' => true]);
});





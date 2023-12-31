<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ZoomClassesController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\CertificateController;
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

//get categories
Route::get('/categories', [CategoryController::class, 'index']);
//get categories count
Route::get('/categories/count',[CategoryController::class,'getCount']);
//get categories by id
Route::get('/categories/{id}', [CategoryController::class, 'show'])->where('id', '[0-9]+');
// get courses of specific category
Route::get('/categories/courses/{id}', [CategoryController::class, 'showCategoryCourses']);
//get courses of a trainer
Route::get('/trainer/courses/{id}',[TrainerController::class,'getCoursesByTrainerId']);

// routes for student
Route::get('/students',[StudentController::class, 'index']);
Route::get('/students/count',[StudentController::class,'getCount']);
Route::get('/students/{id}',[StudentController::class, 'show']);
Route::post('/student/register',[StudentController::class,'register']);
Route::post('/student/login', [StudentController::class, 'login'])->middleware('verify-email');

Route::middleware('checkStudent:students')->group(function () {
    Route::post('/student/me', [StudentController::class, 'me']);
    Route::post('/student/logout', [StudentController::class, 'logout']);
    Route::post('/student/hello', [StudentController::class, 'sayHello']);
    Route::post('/students/{id}',[StudentController::class, 'update']);
    Route::post('/courses/{course}/feedback',  [FeedbackController::class, 'store']);
    Route::patch('/courses/{courseId}/feedback/{feedbackId}', [FeedbackController::class, 'update']);
    Route::put('/courses/{course}/feedback/{feedback}', [FeedbackController::class, 'update']);
    Route::get('courses/{courseId}/exams/{examId}', [ExamController::class, 'showExam']);
    Route::get('/courses/{courseId}/exams/{examId}/degree', [ExamController::class, 'getExamDegree']);
    Route::post('/courses/{courseId}/exams/{examId}/degree', [ExamController::class, 'storeExamDegree']);
    Route::put('/courses/{courseId}/progress', [CourseController::class, 'updateProgress']);
    Route::get('/courses/{course}/progress', [CourseController::class, 'getProgress']);
    Route::get('/courses/{course}/completion', [CourseController::class, 'completeCourse']);
    Route::get('/student/courses/{id}',[StudentController::class,'getCoursesByStudentId']);
    //show courses by student id
    Route::get('/student/showCourses/{id}', [CourseController::class, 'showCourses']);
    //enroll
    Route::post('/student/storeCourse',[CourseController::class,'Enrollment']);
});

// routes for trainer
Route::get('/trainers', [TrainerController::class, 'index']);
Route::get('/trainers/count',[TrainerController::class,'getCount']);
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

    // routes for zoom classes
    Route::post('/zoom_classes', [ZoomClassesController::class, 'store']);
    Route::delete('/zoom_classes/{id}', [ZoomClassesController::class, 'destroy']);
});

//get courses count
Route::get('/courses/count',[CourseController::class,'getCount']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show'])->where('id', '[0-9]+');
Route::get('/student/studentCount/{id}', [CourseController::class, 'studentCount']);
Route::post('/studentcourseenroll', [CourseController::class, 'course_student_enroll']);

Route::middleware('adminOrStudent:students,api')->group(function () {
    Route::delete('/courses/{course}/feedback/{feedback}', [FeedbackController::class, 'destroy']);
});

Route::middleware('studentOrTrainer:students,trainers')->group(function () {
    Route::get('/courses/{courseId}/exams', [ExamController::class, 'getAllCourseExams']);
    Route::get('/courses/{courseId}/exams-with-questions', [ExamController::class, 'getAllCourseExamsWithQuestions']);
    Route::get('/exams/{examId}/questions', [ExamController::class, 'getAllExamQuestions']);
    Route::get('/course_content/show/{c_id}', [CourseController::class, 'showvideo']);
    Route::get('/zoom_classes/{course_id}', [ZoomClassesController::class, 'index']);
});

Route::post('/contact_us', [ContactUsController::class, 'store']);

Route::get('/courses/{course}/feedback', [FeedbackController::class, 'show']);


Route::post('payment-intent', [PaymentController::class,'CreatePayIntent']);
Route::post('store-intent', [PaymentController::class,'storeStripePayment']);
Route::get('/courses/{course}/feedbacks', [FeedbackController::class, 'show']);


// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', function (Request $request) {
    $credentials = $request->only('email', 'password');
    $user = Student::where('email', $credentials['email'])->first();

    if ($user) {
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email is already verified.'], 200);
        }

        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent!'], 200);
    }
})->middleware(['throttle:6,1'])->name('verification.send');

Route::get('/verify/certificate/{studentId}/{courseId}/{verificationNumber}', [CertificateController::class,'verify'])->name('verify.certificate');

Route::post('messages', [chatController::class, 'message']);

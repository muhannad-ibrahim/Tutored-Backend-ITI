<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Support\Facades\Redirect;

class CertificateController extends Controller
{
    public function verify($studentId, $courseId, $verificationNumber)
    {
        $certificate = Certificate::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('verification_number', $verificationNumber)
            ->first();

        if ($certificate) {
            $student = Student::find($studentId);
            $course = Course::find($courseId);
    
            $message = [
                'message' => 'Verified',
                'student_name' => $student->fname . ' ' . $student->lname,
                'course_name' => $course->name,
                'verification_number' => $verificationNumber
            ];
    
            return response()->json($message, 200);
        } else {
            $message = [
                'message' => 'Unverified',
                'student_name' => null,
                'course_name' => null,
                'verification_number' => $verificationNumber
            ];
    
            return response()->json($message, 200);
        }
    }
}

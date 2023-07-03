<?php

namespace App\Http\Controllers;

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
            return redirect(env('FRONT_URL') . '/main/login/student');
            // return response()->json(['message' => 'Verified'], 200);
        } else {
            return redirect(env('FRONT_URL') . '/main/login/student');
            // return response()->json(['message' => 'Unverified'], 200);
        }
    }
}

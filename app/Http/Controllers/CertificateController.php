<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;

class CertificateController extends Controller
{
    public function verify($studentId, $courseId, $verificationNumber)
    {
        $certificate = Certificate::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('verification_number', $verificationNumber)
            ->first();

        if ($certificate) {
            return response()->json(['message' => 'Verified'], 200);
        } else {
            return response()->json(['message' => 'Unverified'], 200);
        }
    }
}

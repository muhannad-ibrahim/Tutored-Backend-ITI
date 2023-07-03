<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;

class CertificateController extends Controller
{
    public function verify($studentId, $courseId, $verificationNumber)
    {
        // Check if the certificate exists with the provided student ID, course ID, and verification number
        $certificate = Certificate::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('verification_number', $verificationNumber)
            ->first();

        if ($certificate) {
            // Certificate is valid, display the verification status
            return view('certificateVerification', ['verificationStatus' => 'verified']);
        } else {
            // Certificate is invalid, display the verification status
            return view('certificateVerification', ['verificationStatus' => 'unverified']);
        }
    }
}

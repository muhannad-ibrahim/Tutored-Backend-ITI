<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $certificateData;

    public function __construct($certificateData)
    {
        $this->certificateData = $certificateData;
    }

    public function build()
    {
        return $this->view('emails.certificate')
                    ->with('student_name', $this->certificateData['student_name'])
                    ->with('course_name', $this->certificateData['course_name']);

    }
}


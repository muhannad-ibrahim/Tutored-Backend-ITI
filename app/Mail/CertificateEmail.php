<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Dompdf\Dompdf;

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
        $pdf = $this->generateCertificatePdf();
        return $this->view('emails.certificate')
                    ->with('student_name', $this->certificateData['student_name'])
                    ->with('course_name', $this->certificateData['course_name'])
                    ->attachData($pdf, 'certificate.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    public function generateCertificatePdf()
    {
        $html = view('emails.certificatePdf', $this->certificateData)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}


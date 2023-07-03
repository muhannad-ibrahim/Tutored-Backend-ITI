<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        // dd($this->certificateData['student_name']);
        $pdf = $this->generateCertificatePdf();
        return $this->view('emails.certificate')
                    ->with('student_name', $this->certificateData['student_name'])
                    ->with('course_name', $this->certificateData['course_name'])
                    ->with('completion_date', $this->certificateData['completion_date'])
                    ->with('verification_number', $this->certificateData['verification_number'])
                    ->with('student_id', $this->certificateData['student_id'])
                    ->with('course_id', $this->certificateData['course_id'])
                    ->attachData($pdf, 'certificate.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    public function generateCertificatePdf()
    {
        $html = view('emails.certificatePdf', $this->certificateData)->render();

        $options = new Options();
        $options->set('chroot', public_path());

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}


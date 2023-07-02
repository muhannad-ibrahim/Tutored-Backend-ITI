<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }

        .certificate-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 2px solid #009933;
            padding: 40px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .certificate-title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #009933;
        }

        .student-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 40px;
            color: #333333;
        }

        .course-name {
            font-size: 24px;
            margin-bottom: 40px;
            color: #666666;
        }

        .message {
            font-size: 18px;
            color: #444444;
        }

        .signature {
            font-size: 18px;
            font-style: italic;
            color: #888888;
            margin-top: 40px;
        }

        .logo {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <img class="logo" src="{{ public_path('images/certificate_logo.png') }}" alt="Certificate Logo">
        <h1 class="certificate-title">Certificate of Completion</h1>
        <p class="student-name">Dear {{ $student_name }},</p>
        <p class="message">Congratulations! You have successfully completed the course:</p>
        <p class="course-name">{{ $course_name }}</p>
        <p class="message">We acknowledge your dedication and commitment to learning.</p>
        <p class="signature">Best regards,<br> The Tutored Team</p>
    </div>
</body>
</html>
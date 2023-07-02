<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutored - Certificate of Completion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        .certificate {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .certificate h1 {
            text-align: center;
            color: #333333;
        }

        .certificate p {
            font-size: 16px;
            line-height: 24px;
            margin-bottom: 10px;
        }

        .certificate .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .certificate .logo img {
            max-width: 200px;
        }

        .certificate .student-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .certificate .course-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .certificate .message {
            text-align: center;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .certificate .signature {
            text-align: right;
            font-size: 18px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Certificate of Completion</h1>
        <p class="student-name">Dear {{ $student_name }},</p>
        <p class="course-name">Congratulations on completing the course:</p>
        <p class="course-name">{{ $course_name }}</p>
        <p class="message">We hereby acknowledge that you have successfully completed all the requirements and assessments of the course. Your dedication and hard work are commendable.</p>
        <p class="signature">Best regards,<br> The Tutored Team</p>
    </div>
</body>
</html>
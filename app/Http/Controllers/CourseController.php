<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\CertificateEmail;
use Illuminate\Support\Facades\View;

class CourseController extends Controller
{
    use ApiResponseTrait;


    public function index()
    {
        //
        $Courses = Course::with('category', 'trainer')->get();
        return response()->json($Courses, 200);
    }


    public function show($id)
    {
        $course = Course::with(['category', 'trainer'])->find($id);
        if ($course) {

            return response()->json($course, 200);
        }
        return response()->json("Not Found", 404);
    }



    public function store(Request $request)
    {

        $validation = $this->validation($request);
        if ($validation instanceof Response) {
            return $validation;
        }

        // $img = $request->file('img');
        // $ext = $img->getClientOriginalExtension();
        // $image = "course -" . uniqid() . ".$ext";
        // $img->move(public_path("uploads/courses/"), $image);
        $image = cloudinary()->upload($request->file('img')->getRealPath())->getSecurePath();

        $course = Course::create([
            'name' => $request->name,
            'img' => $image,
            'category_id' => $request->category_id,
            'trainer_id' => $request->trainer_id,
            'price' => $request->price,
            'duration' => $request->duration,
            'preq' => $request->preq,
            'desc' => $request->desc,
        ]);

        if ($course) {
            return response()->json($course, 200);
        }

        return response()->json("Cannot add this course", 400);
    }



    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        if ($course) {
            // Validation for POST method
            if ($request->isMethod('post')) {
                $validation = $this->apiValidation($request, [
                    'name' => 'required|min:3|max:30',
                    'img' => 'image|mimes:jpeg,png',
                    'price' => 'required',
                    'category_id' => 'exists:categories,id',
                    'trainer_id' => 'required|exists:App\Models\Trainer,id',
                    'duration' => 'required',
                    'desc' => 'required|min:3',
                ]);
                if ($validation instanceof Response) {
                    return $validation;
                }
            }

            $image = $course->img;
            if ($request->hasFile('img')) {
                if ($image !== null) {
                    $path_parts = pathinfo(basename($image));
                    Cloudinary::destroy($path_parts['filename']);
                }
                $image = Cloudinary::upload($request->file('img')->getRealPath())->getSecurePath();
            }

            Log::alert($request->category_id);

            if ($request->category_id == 0) {
                $category_id = $course->category_id;
            } else {
                $category_id = $request->category_id;
            }

            // Update the course data
            $course->name = $request->input('name', $course->name); // Use existing name if not provided in PATCH request
            $course->img = $image;
            $course->category_id = $category_id;
            $course->trainer_id = $request->trainer_id;
            $course->price = $request->price;
            $course->duration = $request->duration;
            $course->preq = $request->preq;
            $course->desc = $request->desc;
            $course->save();

            return response()->json($course, 200);
        }

        return response()->json("Record not found", 404);
    }


    public function destroy($id)
    {
        $course = Course::find($id);
        if (is_null($course)) {
            return response()->json("Record not found", 404);
        }

        $course->delete();
        $img_name = $course->img;
        if ($img_name !== null) {
            $path_parts = pathinfo(basename($img_name));

            Cloudinary::destroy($path_parts['filename']);
        }
        return response()->json(null, 204);
    }



    public function showCourses($id)
    {
        $data = Student::with(['Courses'])->find($id);
        if ($data) {

            return response()->json($data, 200);
        }
        return response()->json("Not Found", 404);
    }


    public function showvideo($e_id)
    {


        $course = DB::select("select * from course__contents where course_id = $e_id");
        if ($course) {

            return response()->json($course, 200);
        }
        return response()->json("Not Found", 404);
    }


    public function showStudent($id)
    {
        $data = Course::with(['students'])->find($id);
        if ($data) {

            return response()->json($data, 200);
        }
        return response()->json("Not Found", 404);
    }



   public function studentCount($id)
    {

        $data = DB::table('course_student')->select('student_id')->where('course_id', '=', $id)->count('student_id');

        if ($data == 0)
            return response()->json($data, 200);
        if ($data) {
            return response()->json($data, 200);
        }
        return response()->json("Not Found", 404);
    }


    public function course_student_enroll(Request $request){

            $course_id=$request->course_id;
            $student_id=$request->student_id;

            $status=DB::select("select * from course_student where course_id = $course_id and student_id = $student_id");
            if ($status) {
                return response()->json($status, 200);
            }
            else{
            return response()->json("Not Found", 404);
            }
    }





        public function getCount(){
            $data = DB::table('courses')->select('id')->count('id');
            if ($data == 0)
                return response()->json($data, 200);
            if ($data) {
                return response()->json($data, 200);
            }
            return response()->json("Not Found", 404);
        }




        public function searchCourse(Request $request)
        {
            $query = Course::query();
            $data = $request->input('search_course');
            if ($data) {
                $query->whereRaw("name LIKE '%" . $data . "%'");
            }
            //$query->get();
            return response()->json($query->get());
        }




    public function Enrollment(Request $request)
    {
        $enrolle = DB::table('course_student')->insert([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
        ]);

        $course=Course::find($request->course_id);
        $course_name=$course->name;
        if ($enrolle) {
            // $course= DB::select("select name from courses where id = $request->course_id");
            $details = [
                'title' => 'Congratulations',
                'body' => "You have enrolled successfully $course_name ",
            ];

            // $email = DB::select("select email from students where id = $request->student_id");

            // Mail::to($email)->send(new welcomemail($details));

            return response()->json($enrolle, 200);
        }

        return response()->json("Cannot add this course", 400);
    }

    public function updateProgress(Request $request, $courseId)
    {
        $student = Auth::guard('students')->user();

        $validator = Validator::make($request->all(), [
            'progress' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        $studentCourse = DB::table('course_student')
            ->where('student_id', $student->id)
            ->where('course_id', $courseId)
            ->first();

        if (!$studentCourse) {
            return response()->json([
                'message' => 'Course not found for the specified student.',
            ], 404);
        }

        DB::table('course_student')
            ->where('student_id', $student->id)
            ->where('course_id', $studentCourse->course_id)
            ->update(['progress' => $validatedData['progress']]);

        return response()->json([
            'message' => 'Course progress updated successfully.',
        ], 200);
    }

    public function getProgress($courseId)
    {
        $student = Auth::guard('students')->user();

        $studentCourse = DB::table('course_student')
            ->where('student_id', $student->id)
            ->where('course_id', $courseId)
            ->first();

        if (!$studentCourse) {
            return response()->json([
                'message' => 'Course not found for the specified student.',
            ], 404);
        }

        return response()->json([
            'progress' => $studentCourse->progress,
        ], 200);
    }

    public function completeCourse($courseId)
    {
        $student = Auth::guard('students')->user();

        $progress = DB::table('course_student')
            ->where('student_id', $student->id)
            ->where('course_id', $courseId)
            ->value('progress');

        if ($progress == 100) {
            $course = DB::table('courses')
            ->where('id', $courseId)
            ->select('name')
            ->first();

            $examDegree = DB::table('student_subject_exam')
            ->join('exams', 'student_subject_exam.exam_id', '=', 'exams.id')
            ->where('exams.course_id', $courseId)
            ->where('student_subject_exam.student_id', $student->id)
            ->select('student_subject_exam.degree', 'student_subject_exam.updated_at')
            ->first();

            if ($examDegree && $examDegree->degree >= 70) {
                // Check if the certificate already exists for the student and course
                $certificate = DB::table('certificates')
                ->where('student_id', $student->id)
                ->where('course_id', $courseId)
                ->first();

                if (!$certificate) {
                    $verificationNumber = uniqid();
                }

                DB::table('certificates')->insert([
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                    'verification_number' => $verificationNumber,
                ]);

                // Generate the certificate and send it to the student's email
                $certificateData = [
                    'student_name' => $student->fname . ' ' . $student->lname,
                    'course_name' => $course->name,
                    'completion_date' => date('F d, Y', strtotime($examDegree->updated_at)),
                ];

                // Send the certificate email
                Mail::to($student->email)->send(new CertificateEmail($certificateData));
                return response()->json([
                    'message' => 'Congratulations! You have completed the course. The certificate has been sent to your email.',
                ], 200);
            }
        }

        return response()->json([
            'message' => 'Course completion criteria not met.',
        ], 400);
    }

     public function validation($request)
    {
        return $this->apiValidation($request, [
            'name' => 'required|min:3|max:50',
            // 'img' => 'required|image|mimes:jpeg,png',
            'price' => 'required',
            'category_id' => 'required|exists:App\Models\Category,id',
            'trainer_id' => 'required|exists:App\Models\Trainer,id',
            'duration' => 'required',
            // 'preq'
            'desc' => 'required|min:3'
        ]);
    }


}

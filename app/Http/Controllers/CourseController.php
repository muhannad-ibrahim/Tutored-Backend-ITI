<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Course;
use App\Models\Course_Content;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Mail\welcomemail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

            if ($request->isMethod('post')) {

                $validation = $this->apiValidation($request, [
                    'name' => 'required|min:3|max:30',
                    'img' => 'image|mimes:jpeg,png',
                    'price' => 'required',
                    'category_id' => 'exists:categories,id',
                    'trainer_id' => 'required|exists:App\Models\Trainer,id',
                    'duration' => 'required',
                    // 'preq' => '',
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

            if ($request->category_id == 0)
                $category_id = $course->category_id;
            else
                $category_id = $request->category_id;

            $course->update([
                'name' => $request->name,
                'img' => $image,
                'category_id' => $category_id,
                'trainer_id' => $request->trainer_id,
                'price' => $request->price,
                'duration' => $request->duration,
                'preq' => $request->preq,
                'desc' => $request->desc,
            ]);
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

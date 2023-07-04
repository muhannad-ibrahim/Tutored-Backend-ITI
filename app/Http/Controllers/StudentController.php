<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Student;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class StudentController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $students = Student::get();
        return $this->apiResponse($students);
    }

    public function store(Request $request)
    {
        $students = Student::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if ($students) {
            return $this->createdResponse($students);
        }

        return response()->json([
            'message' => "can't create student",
        ], 404);
    }

    public function register(Request $request)
    {
        $validation = $this->validation($request);
        if ($validation instanceof Response) {
            return $validation;
        }

        $students = Student::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($students) {
            event(new Registered($students));
            return $this->createdResponse($students);
        }

        return response()->json([
            'message' => "can't create student",
        ], 422);
    }

    public function show($id)
    {
        $student = Student::find($id);
        if ($student) {
            return $this->apiResponse($student);
        }
        return $this->notFoundResponse();
    }

    public function update(Request $request, $id)
    {
        $validation = $this->apiValidation($request, [
            'fname' => 'required|min:3|max:20',
            'lname' => 'required|min:3|max:20',
            'phone' => 'required|min:10',
            'img' => 'image|mimes:jpeg,png',
        ]);
        if ($validation instanceof Response) {
            return $validation;
        }

        $student = Student::find($id);
        if (!$student) {
            return $this->notFoundResponse();
        }

        $name = $student->img;
        if ($request->hasFile('img')) {
            if ($name !== null) {
                $path_parts = pathinfo(basename($name));
                Cloudinary::destroy($path_parts['filename']);
            }
            $name = Cloudinary::upload($request->file('img')->getRealPath())->getSecurePath();
        }

        $student->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'img' => $name,
        ]);

        if ($student) {
            return $this->createdResponse($student);
        }

        return response()->json([
            'message' => "can't update student",
        ], 422);
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        if ($student) {
            $img_name = $student->img;
            if ($img_name !== null) {
                $path_parts = pathinfo(basename($img_name));
                Cloudinary::destroy($path_parts['filename']);
            }
            $student->delete();
            return $this->deleteResponse();
        }
        return $this->notFoundResponse();
    }

    public function getCount()
    {
        $data = DB::table('students')->select('id')->count('id');
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
            'fname' => 'required|min:3|max:20',
            'lname' => 'required|min:3|max:20',
            'gender' => 'required|',
            'phone' => 'required|unique:students|min:10|max:15', //|regex:/^\+\d{1,3}\s?\d{3,14}$/
            'email' => 'required|email|unique:students',
            'password' => 'required|min:8', //|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/
        ]);
    }

    public function login(Request $request)
    {
        $validator = $this->apiValidation($request, [
            'email' => 'required|exists:students,email',
            'password' => 'required|string',
        ]);

        if ($validator instanceof Response) {
            return $validator;
        }
        $credentials = request(['email', 'password']);
        if (!$token = auth()->guard('students')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->guard('students')->user());
    }


    public function getCoursesByStudentId($id)
    {
        $student = Student::with(['courses' => function ($query) {
            $query->withCount('students');
        }])->find($id);
            
        if ($student) {
            $courses = $student->courses;
    
            return response()->json($courses, 200);
        }
    
        return response()->json("No courses found for this student", 404);
    }

    public function logout()
    {
        auth()->guard('students')->logout();
        return response()->json('Successfully logged out');
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->guard('students')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'name' => Auth::guard('students')->user()->fname,
            'id' => Auth::guard('students')->user()->id,
            'role' => 'isStudent',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('students')->factory()->getTTL() * 1440,
            'id' => Auth::guard('students')->user()->id,
            'role' => 'isStudent',

        ]);
    }

    public function sayHello()
    {
        return response()->json('hello students');
    }
}

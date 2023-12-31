<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class TrainerController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $trainers = Trainer::get();
        return $this->apiResponse($trainers);
    }

    public function store(Request $request)
    {
        $validation = $this->validation($request);
        if($validation instanceof Response){
            return $validation;
        }

        $img=$request->file('img');
        $ext=$img->getClientOriginalExtension();
        $image="train -".uniqid().".$ext";
        $img->move(public_path("uploads/trainer/"),$image);

        $trainers = Trainer::create([
            'fname'=>$request->fname ,
            'lname'=>$request->lname ,
            'gender'=>$request->gender ,
            'phone'=>$request->phone ,
            'img'=>$image,
            'email'=>$request->email ,
            'password'=>Hash::make($request->password),
            'facebook'=>$request->facebook ,
            'twitter'=>$request->twitter ,
            'linkedin'=> $request->linkedin ,
        ]);
        if ($trainers) {
            return $this->createdResponse($trainers);
        }

        return response()->json([
            'message' => "can't create trainer",
        ], 404);
    }



    public function getCoursesByTrainerId($id){
        $trainer = Trainer::find($id);

        if ($trainer) {
            $courses = $trainer->courses->map(function ($course) {
                $course->student_count = $course->students->count();
                unset($course->students);
                return $course;
            });
    
            $trainer->courses = $courses;
    
            return response()->json($trainer, 200);
        }
    
        return response()->json("No courses found for this trainer", 404);
    }


    public function register(Request $request)
    {
        $validation = $this->validation($request);
        if($validation instanceof Response){
            return $validation;
        }

        $trainers = Trainer::create([
            'fname'=>$request->fname ,
            'lname'=>$request->lname ,
            'gender'=>$request->gender ,
            'phone'=>$request->phone ,
            'email'=>$request->email ,
            'img' => 'https://res.cloudinary.com/ddk98mjzn/image/upload/v1684273831/blank-profile-picture-973460_1280_edrkel.webp',
            'password'=>Hash::make($request->password),
        ]);
        if ($trainers) {
            return $this->createdResponse($trainers);
        }

        return response()->json([
            'message' => "can't create trainer",
        ], 422);
    }

    public function show($id)
    {
        $trainer = Trainer::find($id);
        if ($trainer) {
            return $this->apiResponse($trainer);
        }
        return $this->notFoundResponse();
    }

    public function update(Request $request, $id)
    {
        $validation = $this->apiValidation($request, [
            'img' => 'image|mimes:jpeg,png',
        ]);

        if ($validation instanceof Response) {
            return $validation;
        }

        $trainer = Trainer::find($id);

        if (!$trainer) {
            return $this->notFoundResponse();
        }

        $img_name = $trainer->img;

        if ($request->hasFile('img')) {
            if ($img_name !== null) {
                $path_parts = pathinfo(basename($img_name));

                Cloudinary::destroy($path_parts['filename']);
            }
            $img_name = Cloudinary::upload($request->file('img')->getRealPath())->getSecurePath();
        }

        $trainer->update([
            'fname' => $request->input('fname', $trainer->fname),
            'lname' => $request->input('lname', $trainer->lname),
            'phone' => $request->input('phone', $trainer->phone),
            'img' => $img_name,
            'facebook' => $request->input('facebook', $trainer->facebook),
            'twitter' => $request->input('twitter', $trainer->twitter),
            'linkedin' => $request->input('linkedin', $trainer->linkedin),
        ]);

        if ($trainer) {
            return $this->createdResponse($trainer);
        }

        return response()->json([
            'message' => "can't update trainer",
        ], 422);
    }

    public function destroy($id)
    {
        $trainer = Trainer::find($id);
        if ($trainer) {
            $img_name = $trainer->img;

            if ($img_name !== null) {

            $path_parts = pathinfo(basename($img_name));

              Cloudinary::destroy($path_parts['filename']);
            }
            $trainer->delete();
            return $this->deleteResponse();
        }
        return $this->notFoundResponse();
    }

    public function getCount()
    {
        $data = DB::table('trainers')->select('id')->count('id');
        if ($data == 0)
            return response()->json($data, 200);
        if ($data) {
            return response()->json($data, 200);
        }
        return response()->json("Not Found", 404);
    }

    public function validation($request){
        return $this->apiValidation($request , [
            'fname' => 'required|min:3|max:20',
            'lname' => 'required|min:3|max:20',
            'gender' => 'required',
            'phone' => 'required|unique:trainers|min:10|max:15|regex:/^\+\d{1,3}\s?\d{3,14}$/',
            'email' => 'required|email|unique:trainers',
            'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%-_&]).*$/',
        ]);
    }

    public function login(Request $request)
    {
        $validator = $this->apiValidation($request , [
            'email' => 'required|exists:trainers,email' ,
            'password' => 'required|string' ,
        ]);

        if($validator instanceof Response){
            return $validator;
        }

        $credentials = request(['email', 'password']);
        if (!$token = auth()->guard('trainers')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->guard('trainers')->user());
    }


    public function logout()
    {
        auth()->guard('trainers')->logout();
        return response()->json('Successfully logged out');
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->guard('trainers')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'name'=>Auth::guard('trainers')->user()->fname,
            'id'=>Auth::guard('trainers')->user()->id,
            'role'=>'isTrainer',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('trainers')->factory()->getTTL() * 1440
        ],200);
    }

    public function sayHello(){
        return response()->json('hello trainers');
    }
}

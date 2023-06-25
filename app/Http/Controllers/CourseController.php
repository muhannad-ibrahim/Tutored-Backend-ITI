<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Course;


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

















}

<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use App\Models\Course_Content;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;

class CourseContentController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses_contents = Course_Content::with('course')->get();
        return response()->json($courses_contents, 200);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course_Content  $course_Content
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course_content=Course_Content::with('course')->find($id);
        // $course_content = Course_Content::find($id);
        if ($course_content) {
            $course_content->course;
            // return $this->apiResponse($course);
            return response()->json($course_content, 200);
        }
        // return $this->notFoundResponse();
        return response()->json("Not Found", 404);
    }





}

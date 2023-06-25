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




















}

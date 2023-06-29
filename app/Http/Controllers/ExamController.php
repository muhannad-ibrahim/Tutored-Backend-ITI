<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exam::with('course')->get();

        return response()->json(['exams' => $exams], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $trainer = Auth::guard('trainers')->user();
        $course = Course::where('trainer_id', $trainer->id)
        ->where('id', $request->input('course_id'))
        ->first();
        if (!$course) {
            return response()->json(['message' => 'You are not authorized to create an exam for this course.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();

        $exam = Exam::create([
            'title' => $validatedData['title'],
            'course_id' => $validatedData['course_id'],
        ]);

        if (is_null($exam)) {
            return response()->json(['message' => 'Exam creation failed.'], 500);
        }

        return response()->json(['message' => 'Exam created successfully.', 'exam' => $exam], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $trainer = Auth::guard('trainers')->user();
        
        $exam = Exam::where('id', $id)
        ->whereHas('course', function ($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })
        ->first();

        if (!$exam) {
            return response()->json(['message' => 'Exam not found or you are not authorized to update this exam.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();

        $exam->title = $validatedData['title'];
        $exam->save();

        return response()->json(['message' => 'Exam updated successfully.', 'exam' => $exam], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trainer = Auth::guard('trainers')->user();

        $exam = Exam::where('id', $id)
        ->whereHas('course', function ($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })
        ->first();
        if (!$exam) {
            return response()->json(['message' => 'Exam not found or you are not authorized to delete this exam.'], 404);
        }

        $exam->delete();

        return response()->json(['message' => 'Exam deleted successfully.'], 204);
    }

    public function showExam($courseId, $examId)
    {
        $student = Auth::user();

        $enrollment = DB::table('course_student')
        ->where('course_id', $courseId)
        ->where('student_id', $student->id)
        ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        }

        $exam = Exam::where('id', $examId)
        ->where('course_id', $courseId)
        ->first();

        if (!$exam) {
            return response()->json(['message' => 'Exam not found.'], 404);
        }

        $questions = Question::where('exam_id', $examId)->get();

        if ($questions->isEmpty()) {
            return response()->json(['message' => 'No questions found for this exam.'], 404);
        }    

        $examData = [
            'exam' => $exam,
            'questions' => $questions,
        ];

        return response()->json(['message' => 'Exam retrieved successfully.', 'exam' => $examData], 200);
    }

    public function getAllCourseExams($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        $exams = $course->exams()->get();

        return response()->json(['message' => 'Exams retrieved successfully.', 'exams' => $exams], 200);
    }

    public function getAllCourseExamsWithQuestions($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        $exams = $course->exams()->with('questions.choices')->get();

        return response()->json(['message' => 'Exams retrieved successfully.', 'exams' => $exams], 200);
    }
}

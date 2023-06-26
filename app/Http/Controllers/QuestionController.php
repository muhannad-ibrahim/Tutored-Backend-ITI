<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, $examId)
    {
        $trainer = Auth::user();

        $exam = Exam::where('id', $examId)
        ->whereHas('course', function ($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })
        ->first();

        if (!$exam) {
            return response()->json(['message' => 'Exam not found or you are not authorized to add questions to this exam.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'header' => 'required|string',
            'choices' => 'required|array',
            'choices.*' => 'string',
            'score' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        $question = Question::create([
            'header' => $validatedData['header'],
            'score' => $validatedData['score'],
            'exam_id' => $exam->id,
        ]);

        foreach ($validatedData['choices'] as $choice) {
            $question->choices()->create([
                'text' => $choice['text'],
                'is_correct' => $choice['is_correct'],
            ]);
        }

        return response()->json(['message' => 'Question created successfully.', 'question' => $question], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $questionId)
    {
        $trainer = Auth::user();

        $question = Question::whereHas('exam.course', function ($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })
        ->where('id', $questionId)
        ->first();

        if (!$question) {
            return response()->json(['message' => 'Question not found or you are not authorized to update this question.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'header' => 'required|string',
            'choices' => 'required|array',
            'choices.*.text' => 'required|string',
            'choices.*.is_correct' => 'required|boolean',
            'score' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        $question->header = $validatedData['header'];
        $question->score = $validatedData['score'];
        $question->save();

        $question->choices()->delete();

        foreach ($validatedData['choices'] as $choice) {
            $question->choices()->create([
                'text' => $choice['text'],
                'is_correct' => $choice['is_correct'],
            ]);
        }

        return response()->json(['message' => 'Question updated successfully.', 'question' => $question], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($questionId)
    {
        $trainer = Auth::user();

        $question = Question::whereHas('exam.course', function ($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })
        ->where('id', $questionId)
        ->first();

        if (!$question) {
            return response()->json(['message' => 'Question not found or you are not authorized to delete this question.'], 404);
        }

        $question->choices()->delete();
        $question->delete();

        return response()->json(['message' => 'Question deleted successfully.'], 204);
    }
}

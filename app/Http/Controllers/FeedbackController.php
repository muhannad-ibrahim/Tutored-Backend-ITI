<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbacks = Feedback::all();
        return response()->json([
            'message' => 'All feedbacks retrieved successfully.',
            'feedbacks' => $feedbacks,
        ], 200);
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
    public function store(Request $request, Course $course)
    {
        $validatedData = $request->validate([
            'review' => 'nullable|string|min:10|max:512',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $feedback = Feedback::create([
            'review' => $validatedData['review'],
            'rating' => $validatedData['rating'],
            'student_id' => auth()->user()->id,
            'course_id' => $course->id,
        ]);
        $feedback->save();

        $averageRating = $course->feedbacks()->avg('rating');
        $course->average_rating = $averageRating;
        $course->save();

        return response()->json([
            'message' => 'Feedback stored successfully.',
            'feedback' => $feedback,
            'course' => $course,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        $feedbacks = $course->feedbacks;
        return response()->json([
            'message' => 'Feedbacks retrieved successfully for the course.',
            'feedbacks' => $feedbacks,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit(Feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feedback $feedback)
    {
        $validatedData = $request->validate([
            'review' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $feedback->review = $validatedData['review'];
        $feedback->rating = $validatedData['rating'];
        $feedback->save();

        $averageRating = $feedback->course->feedbacks()->avg('rating');
        $feedback->course->average_rating = $averageRating;
        $feedback->course->save();

        return response()->json([
            'message' => 'Feedback updated successfully.',
            'feedback' => $feedback,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        //
    }
}

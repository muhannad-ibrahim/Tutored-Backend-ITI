<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Course $course)
    {
        $existingFeedback = Feedback::where('student_id', auth()->user()->id)
        ->where('course_id', $course->id)
        ->first();

        if ($existingFeedback) {
            return response()->json([
                'message' => 'You have already reviewed this course.',
                'feedback' => $existingFeedback,
                'course' => $course,
            ], 400);
        }

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $courseId, $feedbackId)
    {
        $validatedData = $request->validate([
            'review' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $feedback = Feedback::where('course_id', $courseId)
        ->where('id', $feedbackId)
        ->first();

        if (!$feedback) {
            return response()->json([
                'message' => 'Feedback not found for the specified course.',
            ], 404);
        }

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
    public function destroy(Request $request, $courseId, $feedbackId)
    {   
        dd('fdf');
        $feedback =  Feedback::find($feedbackId);
        if (is_null($feedback)) {
            return response()->json("Feedback not found", 404);
        }

        // Check if the current user is an admin
        if (Auth::guard('api')->check()) {
            // Admin is authorized to delete any feedback
            $feedback->delete();
            return response()->json(['message' => 'Feedback deleted successfully.'], 204);
        }

        // Check if the authenticated user is a student
        if (Auth::guard('students')->check()) {
            // Student can only delete their own feedback
            if ($feedback->student_id == Auth::guard('students')->id()) {
                $feedback->delete();
                return response()->json(['message' => 'Feedback deleted successfully.'], 204);
            } else {
                return response()->json(['message' => 'You are not authorized to delete this feedback.'], 403);
            }
        }

        return response()->json(['message' => 'Please login'], 401);
    }
}

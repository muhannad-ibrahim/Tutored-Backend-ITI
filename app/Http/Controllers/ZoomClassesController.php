<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\zoom_class;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

class ZoomClassesController extends Controller
{
    use ApiResponseTrait;

    public function index($course_id)
    {
        $zoom_classes = zoom_class::where('course_id', $course_id)->get();
        return $this->apiResponse($zoom_classes);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $zoomMeeting = zoom_class::create([
                'trainer_id' => Auth::guard('trainers')->user()->id,
                'course_id' => $request->course_id,
                'meeting_id' => $request->meeting_id,
                'topic' => $request->topic,
                'start_at' => $request->start_time,
                'duration' => $request->duration,
                'password' => $request->password,
                'start_url' => $request->start_url,
                'join_url' => $request->join_url,
            ]);

            $zoomMeeting['id'] = $zoomMeeting->id;

            return $this->createdResponse($zoomMeeting);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        try {
            zoom_class::destroy($id);

            return $this->deleteResponse();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}

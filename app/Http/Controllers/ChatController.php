<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\PrivateChatEvent;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Trainer;

class ChatController extends Controller
{

    public function sendMessage(Request $request)
    {
        if (!Auth::guard('students')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $student = Auth::guard('students')->user();
        $trainerId = $request->input('trainer_id');
        $message = $request->input('message');

        $trainer = Trainer::findOrFail($trainerId);

        $chat = Chat::create([
            'student_id' => $student->id,
            'trainer_id' => $trainer->id,
            'message' => $message,
        ]);

        event(new PrivateChatEvent($student, $trainer, $message));

        return response()->json($chat);
    }

    public function getMessages(Request $request)
    {
        $student = Auth::guard('students')->user();

        if (!$student) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $messages = Chat::where('student_id', $student->id)->get();

        return response()->json($messages);
    }



}

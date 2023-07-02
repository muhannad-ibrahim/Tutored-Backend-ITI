<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\PrivateChatEvent;
use App\Models\Chat;
use App\Models\Student;


class ChatController extends Controller
{
    //

    public function sendMessage(Request $request)
    {
        $user = auth()->user();
        $studentId = $request->input('student_id');
        $message = $request->input('message');

        $student = Student::findOrFail($studentId);

        $chat = Chat::create([
            'user_id' => $user->id,
            'student_id' => $student->id,
            'message' => $message,
        ]);

        event(new PrivateChatEvent($user, $student, $message));

        return response()->json($chat);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatMessageSent;

class ChatMessageController extends Controller
{
    //

    public function sendMessage(Request $request)
    {
        // Validate and process the message

        // Broadcast the message
        event(new ChatMessageSent($message));

        return response()->json(['success' => true]);
    }

}

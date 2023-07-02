<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message;
    public $studentId;
    public $trainerId;
    public function __construct($message, $studentId, $trainerId)
    {
        $this->message = $message;
        $this->studentId = $studentId;
        $this->trainerId = $trainerId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    // public function broadcastOn()
    // {
    //     return new Channel('chat');
    // }

    public function broadcastOn()
{
    return [
        new PrivateChannel('private-chat.student.' . $this->studentId),
        new PrivateChannel('private-chat.trainer.' . $this->trainerId),
    ];
}


public function broadcastWith()
{
    return [
        'message' => $this->message,
        'student_id' => $this->studentId,
        'trainer_id' => $this->trainerId,
    ];
}

}

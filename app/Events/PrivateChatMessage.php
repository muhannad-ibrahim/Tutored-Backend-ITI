<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Student;
use App\Models\Trainer;
class PrivateChatMessage  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $student;
    public $trainer;
    public $message;

    public function __construct(Student $student, Trainer $trainer, $message)
    {
        $this->student = $student;
        $this->trainer = $trainer;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('private-chat');
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Chat;
use App\Models\User;
use App\Models\Student;
use App\Channels\PrivateChatChannel;
use App\Models\Trainer;


class PrivateChatEvent  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

     /**
    * The user instance.
    *
    * @var User
    */
   public $user;

   /**
    * The student instance.
    *
    * @var Student
    */

   /**
    * The message content.
    *
    * @var string
    */

   /**
    * Create a new event instance.
    *
    * @param  User  $user
    * @param  Student  $student
    * @param  string  $message
    * @return void
    */
    public $trainer;
    public $student;
    public $message;

    public function __construct(Student $student,Trainer $trainer,string $message)
    {
        $this->trainer = $trainer;
        $this->student = $student;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('private-chat.'.$this->trainer->id.'.'.$this->student->id);
    }

    public function broadcastAs()
    {
        return 'message-sent';
    }
}

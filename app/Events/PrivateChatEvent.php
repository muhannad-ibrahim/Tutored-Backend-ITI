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
   public $student;

   /**
    * The message content.
    *
    * @var string
    */
   public $message;

   /**
    * Create a new event instance.
    *
    * @param  User  $user
    * @param  Student  $student
    * @param  string  $message
    * @return void
    */
   public function __construct(User $user, Student $student, string $message)
   {
       $this->user = $user;
       $this->student = $student;
       $this->message = $message;
   }

   /**
    * Get the channels the event should broadcast on.
    *
    * @return array
    */
   public function broadcastOn()
   {
       return [
           new PrivateChatChannel($this->user, $this->student),
       ];
   }

   /**
    * Get the broadcast event name.
    *
    * @return string
    */
   public function broadcastAs()
   {
       return 'message-sent';
   }
}

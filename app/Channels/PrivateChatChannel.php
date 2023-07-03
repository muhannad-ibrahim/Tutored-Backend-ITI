<?php

namespace App\Channels;

use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Trainer;

class PrivateChatChannel implements ShouldBroadcast
{
    public $trainer;
    public $student;

    public function __construct(Student $student,Trainer $trainer)
    {
        $this->trainer = $trainer;
        $this->student = $student;
    }

    public function join($request)
    {
        try {
            $trainer = Trainer::findOrFail($this->trainer->id);
            $student = Student::findOrFail($this->student->id);

            if ($trainer->id === $student->trainer_id) {
                return new PrivateChannel('private-chat.'.$trainer->id.'.'.$student->id);
            } else {
                throw new AuthorizationException();
            }
        } catch (ModelNotFoundException $exception) {
            throw new AuthorizationException();
        }
    }

    public function broadcastOn()
    {
        return new PrivateChannel('private-chat.'.$this->trainer->id.'.'.$this->student->id);
    }

    public function __toString()
    {
        return 'private-chat.'.$this->trainer->id.'.'.$this->student->id;
    }

}

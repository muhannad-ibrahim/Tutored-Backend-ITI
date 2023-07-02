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

class PrivateChatChannel implements ShouldBroadcast
{
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
     * Create a new channel instance.
     *
     * @param  User  $user
     * @param  Student  $student
     * @return void
     */
    public function __construct(User $user, Student $student)
    {
        $this->user = $user;
        $this->student = $student;
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|bool
     */
    public function join($request)
    {
        try {
            $user = User::findOrFail($this->user->id);
            $student = Student::findOrFail($this->student->id);

            if ($user->id === $student->user_id) {
                return new PrivateChannel('private-chat.'.$user->id.'.'.$student->id);
            } else {
                throw new AuthorizationException();
            }
        } catch (ModelNotFoundException $exception) {
            throw new AuthorizationException();
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('private-chat.'.$this->user->id.'.'.$this->student->id);
    }
    public function __toString()
{
    return 'private-chat.'.$this->user->id.'.'.$this->student->id;
}


}

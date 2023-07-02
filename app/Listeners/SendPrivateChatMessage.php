<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\PrivateChatMessage;
class SendPrivateChatMessage implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PrivateChatMessage $event)
    {
        // Save the chat message to the database
        // You can access the student, trainer, and message using $event->student, $event->trainer, and $event->message respectively

        // Broadcast the event to the private channel
        broadcast(new PrivateChatMessage($event->student, $event->trainer, $event->message))->toOthers();
    }
}

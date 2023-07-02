<?php

namespace App\Listeners;

use App\Events\SendChatMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChatMessageListener
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
     * @param  \App\Events\SendChatMessage  $event
     * @return void
     */
    public function handle(SendChatMessage $event)
    {
        //
    }
}

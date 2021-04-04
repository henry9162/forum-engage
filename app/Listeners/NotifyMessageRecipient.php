<?php

namespace App\Listeners;

use App\Events\MessageWasRecieved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMessageRecipient
{
    
    /**
     * Handle the event.
     * Recieved message here references the chats model class
     * 
     * @param  MessageWasRecieved  $event
     * @return void
     */
    public function handle(MessageWasRecieved $event)
    {
        $event->chat->notify($event->chat->message->latest()->first());
    }
}

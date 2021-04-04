<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\MessageWasRecievedNotification;

class Chat extends Model
{
    protected $guarded = [];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notify($message){  
        return $this->user->notify(new MessageWasRecievedNotification(auth()->user(), $message));
    }
}

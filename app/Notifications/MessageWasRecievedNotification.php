<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageWasRecievedNotification extends Notification
{
    use Queueable;

    public $user;

    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $message)
    {
        $this->message = $message;

        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->user->username. ' said ' .$this->message->content,
            'notifier' => $this->user,
            'link' => $this->user->chatPath()
        ];
    }
}

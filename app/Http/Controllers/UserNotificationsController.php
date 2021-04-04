<?php

namespace App\Http\Controllers;

class UserNotificationsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Fetch all unread notifications for the user.
     *
     * @return mixed
     */
    public function index()
    {
        return auth()->user()->unreadNotifications()->where('type', '!=', 'App\Notifications\MessageWasRecievedNotification')->get();
    }

    public function chatNotifications()
    {
        return auth()->user()->unreadNotifications()->where('notifiable_id', auth()->id())
                ->where('type', 'App\Notifications\MessageWasRecievedNotification')
                ->get();
    }

    /**
     * Mark a specific notification as read.
     *
     * @param \App\User $user
     * @param int       $notificationId
     */
    public function destroy($user, $notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);

        $notification->markAsRead();

        return json_encode(
            $notification->data
        );
    }
}

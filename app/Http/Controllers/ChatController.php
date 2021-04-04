<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Session;
use App\Http\Resources\ChatResource;
use App\Http\Resources\UserResource;
use App\Events\PrivateChatEvent;
use Carbon\Carbon;
use App\Events\MsgReadEvent;
use App\Events\MessageWasRecieved;


class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('conversations.chats');
    }

    public function getFriends()
    {
        return UserResource::collection(User::where('id', '!=', auth()->id())->get());
    }

    public function send(Session $session, Request $request)
    {
        $message = $session->messages()->create(['content' => $request->content]);

        $chat = $message->createForSend($session->id);

        $message->createForReceive($session->id, $request->to_user);

        broadcast(new PrivateChatEvent($message->content, $chat));

        $recievedMessage = $session->chats->where('type', true)->where('session_id', $session->id);

        event(new MessageWasRecieved($recievedMessage->last()));

        return response($chat->id, 200);
    }

    public function chats(Session $session)
    {
        return ChatResource::collection($session->chats->where('user_id', auth()->id()));
    }

    public function read(Session $session)
    {
        $chats = $session->chats->where('read_at', null)->where('type', 0)->where('user_id', '!=', auth()->id());

        foreach ($chats as $chat) {
            $chat->update(['read_at' => Carbon::now()]);
            broadcast(new MsgReadEvent(new ChatResource($chat), $chat->session_id));
        }
    }

    public function clear(Session $session)
    {
        $session->deleteChats();
        $session->chats->count() == 0 ? $session->deleteMessages() : '';
        return response('cleared', 200);
    }

}

<?php

namespace App\Http\Controllers;

use App\Events\NewMessageSent;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\notification;
use App\Models\User;
use Illuminate\Http\Request;

class chatMessageController extends Controller
{


    public function index(GetMessageRequest $request)
    {
        $data = $request->validated();

        $chatId = $data['chat_id'];
        $currentPage = $data['page'];
        $pageSize = $data['pageSize'] ?? 15;

        $message = ChatMessage::where('chat_id', $chatId)->with('user')->latest('created_at')->simplePaginate($pageSize, ['*'], 'page', $currentPage);

        return $this->success($message->getCollection());
    }

    public function store(storeMessageRequest $request)
    {
        $data = $request->validated();


        $data['user_id'] = auth()->user()->id;

        $chatMessage = ChatMessage::create($data);
        $chatMessage->chat_id = intval($chatMessage->chat_id);
        $chatMessage->load('user');
        $chatMessage['broadcast']= $request['broadcast'] ;

        return $this->sendNotificationToOther($chatMessage);


//        return $this->success($chatMessage, 'Message has been sent successfully');

    }


    public function sendNotificationToOther($chatMessage)
    {


        $user = auth()->user();
        if ($user->isAdmin !=1) {
            broadcast(new NewMessageSent($chatMessage))->toOthers();
        }

        $user_id = $user->id;
        $chat = Chat::where('id', $chatMessage->chat_id)->with(['participants'
        => function ($query) use ($user_id) {
                $query->where('user_id', '!=', $user_id);
            }
        ])->first();
        if (count($chat->participants) > 0) {

            $otherUserId = $chat->participants[0]->user_id;

            $otherUser = User::where('id', $otherUserId)->first();

            $user = auth()->user();

            if ( $chatMessage['broadcast'] != null) {


                $otherUser->update([
                    'isAdmin' => 10
                ]);

                notification::create(
                    [
                        'senderName' => auth()->user()->name,

                        'message' => $chatMessage->message,
                        'user_id' => $otherUserId,
                    ]);

                $otherUser->save();
                $otherUser->sendNewMessageNotification([
                        'messageData' => [
                            'senderName' => $user->name,
                            'message' => $chatMessage->message,]]

                );


            }
            else if ($user->isAdmin == 1) {
                $otherUser->update([
                    'isAdmin' => 1
                ]);

                notification::create(
                    [
                        'senderName' => auth()->user()->name,

                        'message' => $chatMessage->message,
                        'user_id' => $otherUserId,
                    ]);

                $otherUser->save();
                $otherUser->sendNewMessageNotification([
                        'messageData' => [
                            'senderName' => $user->name,
                            'message' => $chatMessage->message,]]

                );


            }
            else {
                $otherUser->sendNewMessageNotification([
                    'messageData' => [
                        'senderName' => $user->name,
                        'message' => $chatMessage->message,
                        'chatId' => $chatMessage->chat_id
                    ]

                ]);
            }
        }


    }


}

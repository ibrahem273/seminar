<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetchatRequest;
use App\Http\Requests\StoreChatRequest;
use App\Models\Chat;
use Illuminate\Http\Request;

class chatController extends Controller
{
    //check if the user and the other user has previous chat or not
    private function getPreviousChat(int $otherUserId)
    {
        $userId = auth()->user()->id;

        return Chat::where('is_private', 1)
            ->whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->whereHas('participants', function ($query) use ($otherUserId) {
                $query->where('user_id', $otherUserId);
            })->first();

    }


    /**
     * Display a listing of the resource.
     */
    public function index(GetchatRequest $request)
    {
        $data = $request->validated();
        $isPrivate = 1;
        if ($request->has('is_private')) {
            $isPrivate = (int)$data['is_private'];
        }

        $chats = Chat::where('is_private', $isPrivate)
            ->HasParticipant(auth()->user()->id)
            ->whereHas('messages')
            ->with('lastMessage.user', 'participants.user')
            ->latest('updated_at')->get();

        return $this->success(['chat' => $chats]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request)
    {
        $data = $request->validated();

        $data = $this->prepareStoreData($request);
        if ($data['userId'] === $data['otherUserId']) {
            return $this->error('you can not create a chat with your own ');
        }

        $previousChat = $this->getPreviousChat($data['otherUserId']);
        if ($previousChat == null) {
            $chat = Chat::create($data['data']);
            $chat->participants()->createMany([
                    [
                        'user_id' => $data['userId']
                    ],
                    [
                        'user_id' => $data['otherUserId']
                    ]

                ]
            );
            $chat->refresh()->load('participants.user', 'lastMessage.user');
            return $this->success($chat);
        }

        return $this->success($previousChat->load('participants.user', 'lastMessage.user'));
    }

    public function prepareStoreData(StoreChatRequest $request)
    {

        $data = $request->validated();
        $otherUserId = (int)$data['user_id'];
        unset($data['user_id']);
        $data['created_by'] = auth()->user()->id;
        return [
            'otherUserId' => $otherUserId,
            'userId' => auth()->user()->id,
            'data' => $data
        ];

    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        return 123;
        $chat->load('lastMessage.user', 'participants.user');
        return $this->success($chat);
    }

    /**
     * Update the specified resource in storage.
     */
//    public function update(Request $request, string $id)
//    {
//        //
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(string $id)
//    {
//        //
//    }
}

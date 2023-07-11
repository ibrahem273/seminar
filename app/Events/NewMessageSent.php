<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageSent implements   ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private  ChatMessage $chatMessage)
    {

    }
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     *
     */
    public function broadcastOn(): Channel
    {
        return  new PrivateChannel('chat.'.$this->chatMessage->chat_id);
    }

    public function broadcastWith()
    {
        return [
            'chat_id'=>$this->chatMessage->chat_id,
            'message'=>$this->chatMessage->toArray()
        ];
    }
}

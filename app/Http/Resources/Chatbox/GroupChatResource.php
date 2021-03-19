<?php

namespace App\Http\Resources\Chatbox;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'conversationId' => $this->id,
            'channel_name' => 'group-chat-room-'.$this->id,
            'messages' => MessageResource::collection($this->messages),
            'type' => 'Group'
        ];
    }
}

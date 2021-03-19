<?php

namespace App\Http\Resources\Chatbox;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'channel_name' => 'chat-room-'.$this->id,
            //'user' => ($this->first->id == Auth::user()->id) ? new ChatmateResource($this->second) : new ChatmateResource($this->first),
            'messages' => MessageResource::collection($this->messages),
            'type' => 'Individual'
        ];
    }
}

<?php

namespace App\Http\Resources\Chatbox;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'messageable_id' => $this->messageable_id,
            'messageable_type' => $this->messageable_type,
            'user_id' => $this->user_id,
            'text' => $this->text,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sender' => new ChatmateResource($this->sender)
        ];
    }
}

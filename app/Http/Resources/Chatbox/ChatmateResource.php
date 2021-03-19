<?php

namespace App\Http\Resources\Chatbox;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatmateResource extends JsonResource
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
            'id' => $this->id,
            'type' => $this->type,
            'avatar' => $this->profile->avatar,
            'name' => $this->profile->firstname.' '.$this->profile->lastname,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

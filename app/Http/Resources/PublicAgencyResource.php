<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicAgencyResource extends JsonResource
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
            'name' => $this->name,
            'acronym' => $this->acronym,
            'code' => $this->code,
            'website' => $this->website,
            'type' => $this->type,
            'address' => $this->address,
            'contact' => $this->contact,
            'avatar' => $this->avatar
        ];
    }
}

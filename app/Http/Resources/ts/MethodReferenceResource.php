<?php

namespace App\Http\Resources\ts;

use Illuminate\Http\Resources\Json\JsonResource;

class MethodReferenceResource extends JsonResource
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
            'method' => $this->method->name,
            'reference' => $this->reference->name,
            'fee' => $this->fee
        ];
    }
}

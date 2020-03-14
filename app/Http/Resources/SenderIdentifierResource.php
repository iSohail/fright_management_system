<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SenderIdentifierResource extends JsonResource
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
            'type' => 'sender',
            'id' => (string) $this->id,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SenderResource extends JsonResource
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
            'type' => 'Sender',
            'id' => (string)$this->id,
            'attributes' => [
                'name' => (string)$this->name,
                'address' => (string)$this->address,
            ],
            'relationships' => new SenderRelationshipResource($this)
        ];
    }
}

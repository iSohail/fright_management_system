<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerRelationshipResource extends JsonResource
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
            'bilties' => [
                'data' => BiltyIdentifierResource::collection($this->bilties),
            ],
            'sender' => [
                'data' => new SenderIdentifierResource($this->sender),
            ],
            'receiver' => [
                'data' => new ReceiverIdentifierResource($this->receiver),
            ],
        ];
    }
}

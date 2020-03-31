<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralLedgerRelationshipResource extends JsonResource
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
            'customer' => [
                'data' => new CustomerIdentifierResource($this->customer),
            ],
            'user' => [
                'data' => new UserIdentifierResource($this->user),
            ],
        ];
    }
}

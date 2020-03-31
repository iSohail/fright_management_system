<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BiltyRelationshipResource extends JsonResource
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
            'packages' => [
                'data' => PackageIdentifierResource::collection($this->packages)
            ],
            'customer' => [
                'data' => new CustomerIdentifierResource($this->customer)
            ],
            'user' => [
                'data' => new UserIdentifierResource($this->user)
            ],
            'ledger' => [
                'data' => new GeneralLedgerIdentifierResource($this->generalLedger)
            ],
            'challan' => [
                'data' => new ChallanIdentifierResource($this->challan)
            ]
        ];
    }
}

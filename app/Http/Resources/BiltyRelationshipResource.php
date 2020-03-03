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
            'challan' => [
                'data' => new ChallanIdentifierResource($this->challan)
            ]
        ];
    }
}

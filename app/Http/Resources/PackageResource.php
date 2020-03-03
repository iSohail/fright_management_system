<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'type' => 'Package',
            'id' => (string)$this->id,
            'attributes' => [
                'package_no' => (string)$this->package_no,
                'description' => (string)$this->description,
                'unit' => (string)$this->unit,
                'quantity' => (string)$this->quantity,
                'total_weight' => (string)$this->total_weight,
                'rent' => (string)$this->rent,
                'labour' => (string)$this->labour,
            ],
            'relationships' => new PackageRelationshipResource($this)
        ];
    }
}

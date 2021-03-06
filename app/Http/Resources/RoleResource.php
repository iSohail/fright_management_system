<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'type' => 'Role',
            'id' => (string)$this->id,
            'attributes' => [
                'role' => (string)$this->role
            ],
            'relationships' => new RoleRelationshipResource($this)
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'type' => 'User',
            'id' => (string)$this->id,
            'attributes' => [
                'name' => $this->name,
                'role' => $this->role,
                'email' => $this->email,
                'user_name' => $this->user_name
            ],
            'relationships' => new UserRelationshipResource($this)
        ];
    }
}

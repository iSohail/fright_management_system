<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BiltyResource extends JsonResource
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
            'type' => 'Bilty',
            'id' => (string)$this->id,
            'attributes' => [
                'bilty_no' => (string)$this->bilty_no,
                'lg_bl_no' => (string)$this->lg_bl_no,
                'manual' => (string)$this->manual,
                'from' => (string)$this->from,
                'to' => (string)$this->to,
                'sender' => (string)$this->sender,
                'receiver' => (string)$this->receiver,
                'receiver_address' => (string)$this->receiver_address,
                'status' => (string)$this->status,
                'payment_status' => (string)$this->payment_status,
                'bilty_charges' => (string)$this->bilty_charges,
                'local_charges' => (string)$this->local_charges,
                'bilty_total' => (string)$this->bilty_total,
                'packages_total' => (string)$this->packages_total,
                'created_at' => (string)$this->created_at,
                'updated_at' => (string)$this->updated_at,
            ],
            'relationships' => new BiltyRelationshipResource($this)
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChallanResource extends JsonResource
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
            'type' => 'Challan',
            'id' => (string) $this->id,
            'attributes' => [
                'challan_no' => (string) $this->challan_no,
                'truck_no' => (string) $this->truck_no,
                'from' => (string) $this->from,
                'to' => (string) $this->to,
                'permit_no' => (string) $this->permit_no,
                'transport' => (string) $this->transport,
                'driver_name' => (string) $this->driver_name,
                'agent_name' => (string) $this->agent_name,
                'cnic' => (string) $this->cnic,
                'total_amount' => (string) $this->total_amount,
                'expenses' => (string) $this->expenses,
                'grand_total' => (string) $this->grand_total,
                'created_at' => (string) $this->created_at,
                'updated_at' => (string) $this->updated_at,
            ],
            'relationships' => new ChallanRelationshipResource($this),
        ];
    }
}

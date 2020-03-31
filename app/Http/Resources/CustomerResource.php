<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'type' => 'Customer',
            'id' => (string) $this->id,
            'attributes' => [
                'name' => (string) $this->name,
                'cell_no' => (string) $this->cell_no,
                'company' => (string) $this->company,
                'email' => (string) $this->email,
                'customer_no' => (string) $this->customer_no,
                'per_kg_rate' => (string) $this->per_kg_rate,
                'per_cbm_rate' => (string) $this->per_cbm_rate,
                'per_pck_rate' => (string) $this->per_pck_rate,
                'income_tax' => (string) $this->income_tax,
                'sales_tax' => (string) $this->sales_tax,
            ],
            'relationships' => new CustomerRelationshipResource($this),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralLedgerResource extends JsonResource
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
            'type' => 'general_ledger',
            'id' => (string) $this->id,
            'attributes' => [
                'ledger_no' => (string) $this->ledger_no,
                'description' => (string) $this->description,
                'income_tax' => (string) $this->income_tax,
                'sales_tax' => (string) $this->sales_tax,
                'bilty_total' => (string) $this->bilty_total,
                'account_receivable' => (string) $this->account_receivable,
                'amount_paid' => (string) $this->amount_paid,
                'pending_amount' => (string) $this->pending_amount,
                'created_at' => (string) $this->created_at,
                'status' => (string) $this->status,
                'paid_on' => (string) $this->paid_on,
            ],
            'relationships' => new GeneralLedgerRelationshipResource($this),
        ];
    }
}

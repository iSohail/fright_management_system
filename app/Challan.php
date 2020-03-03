<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'id', 'challan_no', 'from', 'to', 'truck_no', 'permit_no', 'transport', 'driver_name', 'agent_name', 'cnic', 'total_amount', 'expenses', 'grand_total',
    ];

    public function bilties()
    {
        return $this->hasMany('App\Bilty');
    }
}

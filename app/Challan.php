<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Challan extends Model
{
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'challan_no', 'from', 'to', 'truck_no', 'permit_no', 'transport', 'driver_name', 'agent_name', 'cnic', 'total_amount', 'expenses', 'grand_total',
    ];

    public function bilties()
    {
        return $this->hasMany('App\Bilty');
    }

    public static function boot()
    {
         parent::boot();
         self::creating(function($model){
             $model->id = self::generateUuid();
         });
    }
    public static function generateUuid()
    {
         return Uuid::generate()->string;
    }
}

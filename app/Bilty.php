<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;


class Bilty extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'bilty_no', 'lg_bl_no', 'from', 'to', 'sender', 'receiver', 'receiver_address', 'status', 'payment_status', 'bilty_charges', 'local_charges', 'customer_id', 'challan_id',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function challan()
    {
        return $this->belongsTo('App\Challan');
    }

    public function packages()
    {
        return $this->hasMany('App\Package');
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

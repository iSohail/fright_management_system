<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Bilty extends Model
{
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'bilty_no', 'lg_bl_no', 'description', 'from', 'to', 'manual', 'sender', 'receiver', 'receiver_address', 'status', 'payment_status', 'bilty_charges', 'local_charges', 'bilty_total', 'packages_total', 'customer_id', 'user_id', 'challan_id', 'ledger_id', 'lock',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function generalLedger()
    {
        return $this->belongsTo('App\GeneralLedger', 'ledger_id');
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
        self::creating(function ($model) {
            $model->id = self::generateUuid();
        });
    }
    public static function generateUuid()
    {
        return Uuid::generate()->string;
    }
}

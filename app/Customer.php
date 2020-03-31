<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Customer extends Model
{
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'name', 'cell_no', 'customer_no', 'company', 'email', 'per_kg_rate', 'per_cbm_rate', 'per_pck_rate', 'income_tax', 'sales_tax'
    ];

    public function bilties()
    {
        return $this->hasMany('App\Bilty');
    }

    public function generalLedgers()
    {
        return $this->hasMany('App\GeneralLedger');
    }

    public function sender()
    {
        return $this->hasOne('App\Sender');
    }

    public function receiver()
    {
        return $this->hasOne('App\Receiver');
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

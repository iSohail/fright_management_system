<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Customer extends Model
{
    public $timestamps = true;
    public $incrementing = false;
    
    protected $fillable = [
        'name', 'cell_no', 'customer_no', 'per_kg_rate', 'per_cbm_rate', 'per_pck_rate'
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

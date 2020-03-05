<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Package extends Model
{
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'bilty_id', 'package_no', 'description', 'unit', 'quantity', 'total_weight', 'rent', 'labour'
    ];

    public function bilty()
    {
        return $this->belongsTo('App\Bilty');
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

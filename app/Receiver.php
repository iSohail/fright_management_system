<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Receiver extends Model
{
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'name', 'address', 'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
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

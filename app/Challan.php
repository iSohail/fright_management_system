<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Webpatser\Uuid\Uuid;

class Challan extends Model
{
    use Searchable;
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'challan_no', 'from', 'to', 'truck_no', 'permit_no', 'transport', 'driver_name', 'agent_name', 'cnic', 'total_amount', 'expenses', 'grand_total', 'user_id',
    ];

    public function bilties()
    {
        return $this->hasMany('App\Bilty');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
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

    // tnt search with scout
    public function searchableAs()
    {
        return 'challans_index';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

         // search for user_name in relationship...
         $array['user_name'] = $this->user['user_name'];

        return $array;
    }
}

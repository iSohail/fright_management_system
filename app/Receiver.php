<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Webpatser\Uuid\Uuid;

class Receiver extends Model
{
    use Searchable;
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

    // tnt search with scout
    public function searchableAs()
    {
        return 'receivers_index';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // search for customer_no in relationship...
        $array['customer_no'] = $this->customer['customer_no'];

        return $array;
    }
}

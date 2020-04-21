<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Laravel\Scout\Searchable;

class Customer extends Model
{
    use Searchable;
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'name', 'cell_no', 'customer_no', 'company', 'email', 'per_kg_rate', 'per_cbm_rate', 'per_pck_rate', 'income_tax', 'sales_tax',
    ];

    public function bilties()
    {
        return $this->hasMany('App\Bilty');
    }

    public function generalLedgers()
    {
        return $this->hasMany('App\GeneralLedger');
    }

    public function senders()
    {
        return $this->hasMany('App\Sender');
    }

    public function receivers()
    {
        return $this->hasMany('App\Receiver');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = self::generateUuid();
        });
        // for saving records with null values
        static::saving(function ($model) {
            foreach ($model->attributes as $key => $value) {
                if ($value !== 0) {
                    $model->{$key} = empty($value) ? null : $value;
                }
            }
        });
    }
    public static function generateUuid()
    {
        return Uuid::generate()->string;
    }

    // tnt search with scout
    public function searchableAs()
    {
        return 'customers_index';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Webpatser\Uuid\Uuid;

class GeneralLedger extends Model
{
    use Searchable;
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'ledger_no', 'description', 'income_tax', 'user_id', 'sales_tax', 'bilty_total', 'account_receivable', 'amount_paid', 'pending_amount', 'status', 'paid_on', 'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function bilties()
    {
        return $this->hasMany('App\Bilty', 'ledger_id');
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
        return 'ledgers_index';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // search for user_name in relationship...
        $array['user_name'] = $this->user['user_name'];
        
        return $array;
    }
}

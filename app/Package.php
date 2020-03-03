<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'id', 'bilty_id', 'package_no', 'description', 'unit', 'quantity', 'total_weight', 'rent', 'labour'
    ];

    public function bilty()
    {
        return $this->belongsTo('App\Bilty');
    }
}

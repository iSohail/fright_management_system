<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Role extends Model
{
    use Searchable;

    protected $fillable = [
        'id', 'role',
    ];

    public function user()
    {
        return $this->hasOne('App\User');
    }

    // tnt search with scout
    public function searchableAs()
    {
        return 'roles_index';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }
}

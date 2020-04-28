<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Webpatser\Uuid\Uuid;

class User extends Authenticatable implements JWTSubject
{
    use Searchable;

    use Notifiable;

    public $timestamps = true;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'user_name', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function _role()
    {
        return $this->belongsTo('App\Role', 'role');
    }

    public function bilties()
    {
        return $this->hasMany('App\Bilty');
    }
    public function challans()
    {
        return $this->hasMany('App\Challan');
    }
    public function generalLedgers()
    {
        return $this->hasMany('App\GeneralLedger');
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // tnt search with scout
    public function searchableAs()
    {
        return 'users_index';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // search for customer_no in relationship...
        $array['role'] = $this->_role['role'];

        return $array;
    }

}

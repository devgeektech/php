<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.

     *

     * @var array
     */
    protected $fillable = [
        'username', 'social_id', 'first_name', 'last_name', 'login_type', 'is_active', 'api_token', 'password', 'email', 'city', 'country', 'country_code', 'gender', 'DOB',
    ];

    protected $searchableColumns = [
    'first_name' => 20,
    'last_name' => 15,
    'email' => 10,
    'username' => 10,
    'country' => 5,
    'city' => 5,
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
     * Get the identifier that will be stored in the subject claim of the JWT.

     *

     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.

     *

     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function items()
    {
        return $this->hasMany(Items::class, 'user_id', 'id')->select(array('id', 'user_id', 'category_id', 'name', 'price','currency', 'item_type', 'type', 'is_sold', 'description', 'created_at'))->orderBy('id', 'desc');
    }

    public function currency()
    {
        return $this->hasOne(Currencies::class, 'country_code', 'country_code')->select(array('code', 'symbol', 'country_code', 'rate1', 'rate2'));
    }
    // public function items_images() {

    //     return $this->hasManyThrough(Items::class , Item_images::class);
    // }

    public function Likes()
    {
        return $this->hasManyThrough(like::class, Items::class);
    }

    public function scopeSearchWhereIn($query, $ids)
    {
        return $query->whereIn('id', $ids);
    }

    public function scopeGetDeviceInfo($query, $id)
    {
        return $query->select(['device_id', 'device_type'])->where('id', $id)->first();
    }

    public function scopeSearchUserName($query, $id)
    {
        return $query->select(['username'])->where('id', $id)->first();
    }

    public function scopeSearch($query, $id)
    {
        return $query->where('id', $id);
    }

    /*

     * Get all the associated products

     *

     * @return \Illuminate\Database\Eloquent\Relations\HasMany

     */

    // public function products()

    // {

    //     return $this->hasMany(Product::class);

    // }
}

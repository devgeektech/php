<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public $table = 'ratings';
    protected $fillable = [
        'user_id', 'rated_user_id', 'rating', 'item_id',
    ];

    /**
     * The attributes that should be hidden for arrays.

     *

     * @var array
     */
    protected $hidden = [
    ];
}

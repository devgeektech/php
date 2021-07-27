<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class like extends Model
{
    protected $table = 'items_like';
    protected $fillable = [
        'user_id', 'item_id',
    ];

    /**
     * The attributes that should be hidden for arrays.

     *

     * @var array
     */
    protected $hidden = [
    ];
}

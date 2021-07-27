<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    public $table = 'currencies';
    protected $fillable = [
        'code', 'symbol', 'name', 'country', 'country_code'
    ];
}

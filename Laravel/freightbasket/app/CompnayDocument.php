<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompnayDocument extends Model
{
     protected $fillable = [
        'user_id', 'companydocuments',
    ];
    
    public $timestamps = false;
}

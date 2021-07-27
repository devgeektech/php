<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_accounts extends Model
{
    public $table = 'user_accounts';
    public $fillable = ['user_id'];
}

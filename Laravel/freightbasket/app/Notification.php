<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $table = 'notification';
    protected $fillable = [
            'user_id','name','sender_id','status'
   	];
   	
  	public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}

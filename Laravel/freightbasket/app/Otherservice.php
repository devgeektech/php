<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Otherservice extends Model
{
    //

    protected $table = 'otherservices';

    protected $fillable = [
            'user_id','name','description','images','service_type','status'
   	];
   	
   	protected $casts = [
	    'images' => 'array'
  	];

  	public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

     public function hasImage(){
        return $this->images;
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class brands extends Model
{
	 public function car_models(){
        return $this->hasMany(car_models::class,'brand_id','id');
         // return $this->belongsTo(car_models::class)->select(array('id as model_id','name'));
    }
    
}

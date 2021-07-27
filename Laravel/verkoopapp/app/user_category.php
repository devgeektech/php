<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_category extends Model
{
    //
    public $table = "user_categories";

    public function scopeSearch($query, $category_id){
    	return $query->where('category_id', $category_id);
    }

    public function scopeNotUserId($query, $user_id){
    	return $query->where('user_id', '!=',$user_id);	
    }
}

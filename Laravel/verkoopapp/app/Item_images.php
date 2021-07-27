<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item_images extends Model
{
    public $table = "items_images";
    
    public function scopeSearch($query, $keyword)
    {
    	 $keywords = explode(',', $keyword );
    	return $query->Where(function ($query) use($keywords) {
             foreach ($keywords as $key => $value) {             
                $query->orwhere('label', 'like',  '%' . $value .'%');
             }
         	})->groupBy('item_id');
    }
}

<?php
  
namespace App;
  
use Illuminate\Database\Eloquent\Model;
   
class Product extends Model
{
    protected $fillable = [
        'name','user_id', 'slug', 'detail', 'hscode', 'min', 'max', 'product_image', 'contactwithseller'	
    ];

    protected $casts = [
	    'product_image' => 'array'
	  ];
}
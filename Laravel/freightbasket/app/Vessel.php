<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{

	public $timestamps = false;
   	protected $fillable = [
            'vessel_name','nmsi','call_sign','built_date','imo_no','flag',
   	];
}
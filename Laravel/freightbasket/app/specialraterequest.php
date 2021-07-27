<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class specialraterequest extends Model
{
    //
    protected $fillable = ['readyness_date','commodity_name','origin_country','place_of_collect','loading_port','domestic_custom_office','domestic_airport','destination_country','destination_port','final_place_of_delivery','packing_type','number_of_qty','dimensions_cargo','trailer_types','cntr_types','gross_weight','dangerous_cargo','service_category','service_type','user_id'];

    protected $casts = [
        'place_of_collect' => 'array',
        'dangerous_cargo' => 'array'
    ];
}

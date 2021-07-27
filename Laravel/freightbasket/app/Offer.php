<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    //
    protected $table = "offer";

    protected $fillable = [
        'freight_id','user_id', 'customer', 'offer_price', 'custom_note', 'status', 'remember_token'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function freight(){
        return $this->belongsTo('App\Freight', 'freight_id', 'id');
    }

    public function companydetails(){
        return $this->belongsTo('App\CompanyDetail', 'user_id', 'user_id');
    }
}

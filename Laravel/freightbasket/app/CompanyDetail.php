<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
	protected $table = "companydetails";
   	protected $fillable = [
            'user_id','companytype','companyservice','companyname','countryname','companycity','companytax','companyemail','companyphone','companyaddress','companydocuments',
   	];
}

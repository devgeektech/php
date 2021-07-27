<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VesselSchedule extends Model
{

	protected $table = 'vessel_schedules';
	// public $timestamps = false;
   	protected $fillable = [
            'vessel_id','voyage_no','liner_agent','departure_country','departure_port','est_departure_date','arrival_country','	arrival_port','	est_arrival_date','terminal','loading_date','decl_surrender_office','booking_ref_no','warehouse_stuffing_att','container_no','ship_role','cut_off_date','notes',
   	];
}
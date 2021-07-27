<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVesselSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vessel_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('vessel_id');
            $table->string('voyage_no');
            $table->string('ship_role');
            $table->string('liner_agent');
            $table->string('departure_country');
            $table->string('departure_port');
            $table->date('est_departure_date');
            $table->string('arrival_country');
            $table->string('arrival_port');
            $table->date('est_arrival_date');
            $table->string('terminal');
            $table->date('loading_date');
            $table->string('decl_surrender_office');
            $table->date('cut_off_date');
            $table->string('booking_ref_no');
            $table->string('container_no');
            $table->string('warehouse_stuffing_att');
            $table->longText('notes');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vessel_schedules');
    }
}

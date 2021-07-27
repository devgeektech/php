<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialraterequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialraterequests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('readyness_date');
            $table->string('commodity_name');
            $table->string('origin_country');
            $table->json('place_of_collect');
            $table->string('loading_port');
            $table->string('domestic_custom_office')->nullable();
            $table->string('domestic_airport')->nullable();
            $table->string('destination_country');
            $table->string('destination_port');
            $table->string('final_place_of_delivery');
            $table->string('packing_type');
            $table->string('number_of_qty');
            $table->string('dimensions_cargo');
            $table->string('trailer_types')->nullable();
            $table->string('cntr_types')->nullable();
            $table->string('gross_weight');
            $table->json('dangerous_cargo');
            $table->string('service_category');
            $table->string('service_type')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specialraterequests');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFreights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freights', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable($value = true);
            $table->string('service_category')->nullable($value = true);
            $table->string('service_type')->nullable($value = true);
            $table->string('departure_country')->nullable($value = true);
            $table->string('departure_city')->nullable($value = true);
            $table->string('departure_port')->nullable($value = true);
            $table->string('estimate_time')->nullable($value = true);
            $table->string('arriaval_country')->nullable($value = true);
            $table->string('arriaval_city')->nullable($value = true);
            $table->string('arriaval_port')->nullable($value = true);
            $table->string('client_type')->nullable($value = true);
            $table->string('location_type')->nullable($value = true);
            $table->string('freightvalidity')->nullable($value = true);
            $table->string('cost_type')->nullable($value = true);
            $table->string('calculaion')->nullable($value = true);
            $table->string('currency_type')->nullable($value = true);
            $table->string('price')->nullable($value = true);
            $table->string('transhipment_country')->nullable($value = true);
            $table->string('transhipment_port')->nullable($value = true);
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
        Schema::dropIfExists('freights');
    }
}

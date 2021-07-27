<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoodsDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable($value = true);
            $table->string('description_name')->nullable($value = true);
            $table->longText('goods_description')->nullable($value = true);
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
        Schema::dropIfExists('goods_descriptions');
    }
}

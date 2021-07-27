<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToGoodsDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods_descriptions', function (Blueprint $table) {
            $table->string('commercial_invoice_no')->nullable($value = true);
            $table->string('commercial_invoice_date')->nullable($value = true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods_descriptions', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnUserPurchaseAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('user_purchase_advertisement', function (Blueprint $table) {
            $table->tinyInteger('status')->after('image')->default(0)->comment('0 - unapproved, 1 - active, 2 - expired, 3 - rejected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('user_purchase_advertisement', function (Blueprint $table) {
        });
    }
}

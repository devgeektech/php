<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsUserPurchaseAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('user_purchase_advertisement', function (Blueprint $table) {
            $table->timestamp('approved_at')->after('image')->nullable()->comment('When approved by Admin');
            $table->timestamp('valid_upto')->after('approved_at')->nullable();
            $table->timestamp('renewed_at')->after('valid_upto')->nullable();
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

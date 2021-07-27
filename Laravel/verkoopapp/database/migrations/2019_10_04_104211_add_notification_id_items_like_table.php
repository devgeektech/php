<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationIdItemsLikeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('items_like', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_id')->nullable()->after('item_id')->comment('Notification type - 3');
            $table->foreign('notification_id')->references('id')->on('notification_activity')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('items_like', function (Blueprint $table) {
        });
    }
}

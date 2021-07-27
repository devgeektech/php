<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationIdFollowsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('follows', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_id')->nullable()->after('follower_id')->comment('Notification type - 2');
            $table->foreign('notification_id')->references('id')->on('notification_activity')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('follows', function (Blueprint $table) {
        });
    }
}

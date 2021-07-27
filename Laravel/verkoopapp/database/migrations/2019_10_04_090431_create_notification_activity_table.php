<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationActivityTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notification_activity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->default('VerkoopApp');
            $table->string('message');
            $table->tinyInteger('type')->comment('1 - Item, 2 - Follow, 3 - Like, 4 - Rating, 5 - Share Coin, 6 - Comment');
            $table->unsignedInteger('from')->comment('Id of table users');
            $table->unsignedInteger('to')->nullable()->comment('Id of table users, 0 - All Users');
            $table->timestamps();
            $table->foreign('from')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('notification_activity');
    }
}

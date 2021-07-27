<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //============== migration to create friendships table ======================
        Schema::create('friendships', function(Blueprint $table){
          $table->increments('id');
          $table->integer('first_user')->index();
          $table->integer('second_user')->index();
          $table->integer('acted_user')->index();
          $table->enum('status', ['pending', 'confirmed', 'blocked']);
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
        Schema::dropIfExists('friendships');
    }
}

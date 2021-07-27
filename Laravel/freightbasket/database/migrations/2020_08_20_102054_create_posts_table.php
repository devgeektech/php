<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timelines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('message');
            $table->json('images')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
        });

        Schema::create('post_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('timeline_id');
            $table->unsignedBigInteger('comment_user_id');

            $table->text('comment');

            $table->foreign('timeline_id')->references('id')->on('timelines')->onDelete('CASCADE');
            $table->foreign('comment_user_id')->references('id')->on('users')->onDelete('CASCADE');


            $table->timestamps();
        });

        Schema::create('post_likes', function (Blueprint $table) {
            $table->unsignedBigInteger('timeline_id');
            $table->unsignedBigInteger('like_user_id');

            $table->timestamps();

            $table->foreign('timeline_id')->references('id')->on('timelines')->onDelete('CASCADE');
            $table->foreign('like_user_id')->references('id')->on('users')->onDelete('CASCADE');


            $table->primary(['timeline_id', 'like_user_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_likes');
        Schema::dropIfExists('post_comments');
        Schema::dropIfExists('timelines');
    }
}

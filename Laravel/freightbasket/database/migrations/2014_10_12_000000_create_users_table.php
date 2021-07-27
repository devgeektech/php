<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable($value = true); 
            $table->string('address')->nullable($value = true); 
            $table->string('about')->nullable($value = true);
            $table->string('country')->nullable($value = true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('refrence_id')->nullable($value = true);
            $table->tinyInteger('status')->default('1');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

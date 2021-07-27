<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('username')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('website')->nullable();
            $table->string('bio')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('login_type')->nullable();
            $table->string('social_id')->nullable(); 
            $table->string('gender');
            $table->timestamp('DOB');
            $table->string('city')->nullable();
            $table->string('state')->nullable();  
            $table->string('country')->nullable();
            $table->string('city_id')->nullable();
            $table->string('state_id')->nullable();  
            $table->string('country_id')->nullable();
            $table->integer('is_active')->default('1');
            $table->integer('mobile_verified')->default('0');
            $table->timestamp('email_verified_at')->nullable();    
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

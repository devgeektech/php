<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_lists', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable($value = true);
            $table->string('fullname')->nullable($value = true);
            $table->string('phone')->nullable($value = true); 
            $table->string('company_address')->nullable($value = true); 
            $table->string('country')->nullable($value = true);
            $table->string('city')->nullable($value = true);
            $table->string('fax')->nullable($value = true);
            $table->string('vat')->nullable($value = true);
            $table->string('tax_no')->nullable($value = true);
            $table->string('person_incharge')->nullable($value = true);
            $table->string('group_name')->nullable($value = true);
            $table->string('mesis_no')->nullable($value = true);
            $table->longText('multi_user')->nullable($value = true);
            $table->tinyInteger('status')->default('1');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_lists');
    }
}

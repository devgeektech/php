<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanydetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companydetails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->nullable($value = true);
            $table->string('companytype')->nullable($value = true);
            $table->longText('companyservice')->nullable($value = true);
            $table->string('companyname')->nullable($value = true);
            $table->string('countryname')->nullable($value = true);
            $table->string('companycity')->nullable($value = true);
            $table->string('companytax')->nullable($value = true);
            $table->string('companyemail')->nullable($value = true);
            $table->string('companyphone')->nullable($value = true);
            $table->string('companyaddress')->nullable($value = true);
            $table->string('companydocuments')->nullable($value = true);
            $table->tinyInteger('status')->default('1');
            $table->string('tax_adminstration')->nullable($value = true);
            $table->string('officetype')->default('main');
            $table->longText('aboutcompany')->nullable($value = true);
            $table->string('service')->nullable($value = true);
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
        Schema::dropIfExists('companydetails');
    }
}

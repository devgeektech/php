<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompnayDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compnay_documents', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable($value = true);
            $table->string('companydocuments')->nullable($value = true);
            $table->tinyInteger('status')->default('1');
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
        Schema::dropIfExists('compnay_documents');
    }
}

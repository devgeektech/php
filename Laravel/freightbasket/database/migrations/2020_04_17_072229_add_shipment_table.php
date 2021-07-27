<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable($value = true);
            $table->string('shipping_Refrence')->nullable($value = true);
            $table->string('fbl_no')->nullable($value = true);
            $table->string('obl_no')->nullable($value = true);
            $table->string('way_bill_type')->nullable($value = true);
            $table->string('booking_no')->nullable($value = true);
            $table->string('refrence_date')->nullable($value = true);
            $table->string('fright_payment_type')->nullable($value = true);
            $table->string('customer_refrence')->nullable($value = true);
            $table->longText('goods_description')->nullable($value = true);
            $table->string('feeder_vessel')->nullable($value = true);
            $table->string('feeder_voyage')->nullable($value = true);
            $table->string('main_vessel')->nullable($value = true);
            $table->string('main_voyage')->nullable($value = true);
            $table->string('b_l_note')->nullable($value = true);
            $table->string('cargo_type')->nullable($value = true);
            $table->string('service_type')->nullable($value = true);
            $table->string('shipper')->nullable($value = true);
            $table->string('consignee')->nullable($value = true);
            $table->string('notify_company')->nullable($value = true);
            $table->string('contract_company')->nullable($value = true);
            $table->string('trucking_company')->nullable($value = true);
            $table->string('shipping_line')->nullable($value = true);
            $table->string('carrier_agent')->nullable($value = true);
            $table->string('destination_agent')->nullable($value = true);
            $table->string('invoice_company')->nullable($value = true);
            $table->string('booking_company')->nullable($value = true);
            $table->string('supplier')->nullable($value = true);
            $table->string('way_bill_date')->nullable($value = true);
            $table->string('invoice_customer')->nullable($value = true);
            $table->string('fbl_orignal_company')->nullable($value = true);
            $table->string('place_of_receipt')->nullable($value = true);
            $table->string('place_of_loading')->nullable($value = true);
            $table->string('loading_date')->nullable($value = true);
            $table->string('origin_port')->nullable($value = true);
            $table->string('transhipment_port_1')->nullable($value = true);
            $table->string('transhipment_port_2')->nullable($value = true);
            $table->string('port_of_discharge')->nullable($value = true);
            $table->string('discharged_date')->nullable($value = true);
            $table->string('place_of_delivery')->nullable($value = true);
            $table->string('payment_terms')->nullable($value = true);
            $table->string('estimated_departure_date')->nullable($value = true);
            $table->string('sailing_flight_date')->nullable($value = true);
            $table->string('estimated_arrival_date')->nullable($value = true);
            $table->string('arrival_date')->nullable($value = true);
            $table->string('sales_rep')->nullable($value = true);
            $table->string('domestic_custom_place')->nullable($value = true);
            $table->string('container_no')->nullable($value = true);
            $table->string('seal_no')->nullable($value = true);
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
        Schema::dropIfExists('shipments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bilties', function (Blueprint $table) {
            $table->string('id');
            $table->string('bilty_no');
            $table->string('lg_bl_no');
            $table->string('from');
            $table->string('to');
            $table->string('sender');
            $table->string('receiver');
            $table->string('receiver_address');
            $table->enum('status', ['registered', 'dispatched', 'delivered']);
            $table->enum('payment_status', ['paid', 'unpaid', 'monthly']);
            $table->unsignedDecimal('bilty_charges');
            $table->unsignedDecimal('local_charges');
            $table->string('customer_id')->nullable();
            $table->string('challan_id')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->unique('bilty_no');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('challan_id')->references('id')->on('challans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bilties');
    }
}

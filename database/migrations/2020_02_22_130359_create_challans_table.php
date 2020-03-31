<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challans', function (Blueprint $table) {
            $table->string('id');
            $table->string('challan_no');
            $table->string('from');
            $table->string('to');
            $table->string('truck_no');
            $table->string('permit_no');
            $table->string('transport');
            $table->string('driver_name');
            $table->string('agent_name');
            $table->string('cnic');
            $table->unsignedDecimal('total_amount');
            $table->unsignedDecimal('expenses');
            $table->unsignedDecimal('grand_total');
            $table->string('user_id')->nullable();

            $table->timestamps();

            $table->primary('id');
            $table->unique('challan_no');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challans');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id');
            $table->string('name');
            $table->string('cell_no');
            $table->string('customer_no');
            $table->string('company')->nullable();
            $table->unsignedDecimal('per_kg_rate')->nullable();
            $table->unsignedDecimal('per_cbm_rate')->nullable();
            $table->unsignedDecimal('per_pck_rate')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->unique('customer_no');
            $table->unique('cell_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}

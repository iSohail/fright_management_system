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
            $table->string('lg_bl_no')->nullable();
            $table->string('description')->nullable();
            $table->string('from');
            $table->string('to');
            $table->string('sender');
            $table->string('receiver');
            $table->string('receiver_address');
            $table->enum('status', ['registered', 'dispatched', 'delivered']);
            $table->enum('payment_status', ['paid', 'unpaid', 'monthly']);
            $table->enum('manual', ['true', 'false'])->default('false');
            $table->enum('lock', ['true', 'false'])->default('false');
            $table->unsignedDecimal('bilty_charges');
            $table->unsignedDecimal('local_charges');
            $table->unsignedDecimal('income_tax')->default(0);
            $table->unsignedDecimal('sales_tax')->default(0);
            $table->unsignedDecimal('bilty_total');
            $table->unsignedDecimal('packages_total');
            $table->string('customer_id')->nullable();
            $table->string('challan_id')->nullable();
            $table->string('ledger_id')->nullable();
            $table->string('user_id')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->unique(['bilty_no', 'manual']);
            $table->unique('lg_bl_no');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('challan_id')->references('id')->on('challans');
            $table->foreign('ledger_id')->references('id')->on('general_ledgers');
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
        Schema::dropIfExists('bilties');
    }
}

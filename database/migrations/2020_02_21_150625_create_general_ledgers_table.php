<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_ledgers', function (Blueprint $table) {
            $table->string('id');
            $table->string('ledger_no');
            $table->string('description');
            $table->unsignedDecimal('income_tax')->default(0);
            $table->unsignedDecimal('sales_tax')->default(0);
            $table->unsignedDecimal('bilty_total');
            $table->unsignedDecimal('account_receivable');
            $table->unsignedDecimal('amount_paid');
            $table->unsignedDecimal('pending_amount');
            $table->enum('status', ['pending', 'cleared']);
            $table->dateTime('paid_on')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('user_id')->nullable();

            $table->timestamps();
            $table->primary('id');
            $table->foreign('customer_id')->references('id')->on('customers');
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
        Schema::dropIfExists('general_ledgers');
    }
}

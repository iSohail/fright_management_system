<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->string('id');
            $table->string('bilty_id');
            $table->string('package_no');
            $table->string('description')->nullable();
            $table->enum('unit', ['kg', 'cbm', 'pck']);
            $table->unsignedDecimal('quantity');
            $table->unsignedDecimal('total_weight')->nullable();
            $table->unsignedDecimal('rent');
            $table->unsignedDecimal('labour');
            $table->timestamps();

            $table->primary('id');
            $table->unique('package_no');
            $table->foreign('bilty_id')->references('id')->on('bilties');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}

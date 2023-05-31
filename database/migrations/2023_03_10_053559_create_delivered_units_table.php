<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivered_units', function (Blueprint $table) {
            $table->id();
            $table->string('POUID');
            $table->string('CUID');
            $table->string('DUTransferDate');
            $table->string('DUCustomer');
            $table->string('DUCustAddress');
            $table->string('DUBrand');
            $table->string('DUCode');
            $table->string('DUModel');
            $table->string('DUSerialNum');
            $table->string('DUMastHeight');
            $table->string('DURemarks');
            $table->string('DUClass');
            $table->string('DUStatus');
            $table->string('DUDeliveredDate');
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
        Schema::dropIfExists('delivered_units');
    }
};

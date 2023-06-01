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
        Schema::create('unit_brand_new_bats', function (Blueprint $table) {
            $table->id();
            $table->string('BNUID');
            $table->string('BNUBABrand');
            $table->string('BNUBABatType');
            $table->string('BNUBASerialNum');
            $table->string('BNUBACode');
            $table->string('BNUBAAmper');
            $table->string('BNUBAVolt');
            $table->string('BNUBACCable');
            $table->string('BNUBACTable');
            $table->string('BNUwSpareBat1');
            $table->string('BNUSB1Brand');
            $table->string('BNUSB1BatType');
            $table->string('BNUSB1SerialNum');
            $table->string('BNUSB1Code');
            $table->string('BNUSB1Amper');
            $table->string('BNUSB1Volt');
            $table->string('BNUSB1CCable');
            $table->string('BNUSB1CTable');
            $table->string('BNUwSpareBat2');
            $table->string('BNUSB2Brand');
            $table->string('BNUSB2BatType');
            $table->string('BNUSB2SerialNum');
            $table->string('BNUSB2Code');
            $table->string('BNUSB2Amper');
            $table->string('BNUSB2Volt');
            $table->string('BNUSB2CCable');
            $table->string('BNUSB2CTable');
            $table->string('BNUCBrand');
            $table->string('BNUCModel');
            $table->string('BNUCSerialNum');
            $table->string('BNUCCode');
            $table->string('BNUCAmper');
            $table->string('BNUCVolt');
            $table->string('BNUCInput');
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
        Schema::dropIfExists('unit_brand_new_bats');
    }
};

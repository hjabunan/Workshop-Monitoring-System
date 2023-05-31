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
        Schema::create('unit_pull_out_bats', function (Blueprint $table) {
            $table->id();
            $table->string('POUID');
            $table->string('POUBABrand');
            $table->string('POUBABatType');
            $table->string('POUBASerialNum');
            $table->string('POUBACode');
            $table->string('POUBAAmper');
            $table->string('POUBAVolt');
            $table->string('POUBACCable');
            $table->string('POUBACTable');
            $table->string('POUwSpareBat1');
            $table->string('POUSB1Brand');
            $table->string('POUSB1BatType');
            $table->string('POUSB1SerialNum');
            $table->string('POUSB1Code');
            $table->string('POUSB1Amper');
            $table->string('POUSB1Volt');
            $table->string('POUSB1CCable');
            $table->string('POUSB1CTable');
            $table->string('POUwSpareBat2');
            $table->string('POUSB2Brand');
            $table->string('POUSB2BatType');
            $table->string('POUSB2SerialNum');
            $table->string('POUSB2Code');
            $table->string('POUSB2Amper');
            $table->string('POUSB2Volt');
            $table->string('POUSB2CCable');
            $table->string('POUSB2CTable');
            $table->string('POUCBrand');
            $table->string('POUCModel');
            $table->string('POUCSerialNum');
            $table->string('POUCCode');
            $table->string('POUCAmper');
            $table->string('POUCVolt');
            $table->string('POUCInput');
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
        Schema::dropIfExists('unit_pull_out_bats');
    }
};

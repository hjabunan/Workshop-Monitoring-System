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
        Schema::create('cannibalized_units', function (Blueprint $table) {
            $table->id();
            $table->string('CanUnitCONum');
            $table->string('CanUnitBrand');
            $table->string('CanUnitStatus');
            $table->string('CanUnitDate');
            $table->string('CanUnitCFModelNum');
            $table->string('CanUnitCFSerialNum');
            $table->string('CanUnitCFRentalCode');
            $table->string('CanUnitCFSection');
            $table->string('CanUnitCFPIC');
            $table->string('CanUnitCFPrepBy');
            $table->string('CanUnitCFPrepDate');
            $table->string('CanUnitCFStartTime');
            $table->string('CanUnitCFEndTime');
            $table->string('CanUnitITModelNum');
            $table->string('CanUnitITSerialNum');
            $table->string('CanUnitITRentalCode');
            $table->string('CanUnitITCustomer');
            $table->string('CanUnitITCustAddress');
            $table->string('CanUnitITCustArea');
            $table->string('CanUnitITSupMRI');
            $table->string('CanUnitITSupSTO');
            $table->string('CanUnitITRecBy');
            $table->string('CanUnitCPrepBy');
            $table->string('CanUnitRPRetBy');
            $table->string('CanUnitRPRetDate');
            $table->string('CanUnitRPRecBy');
            $table->string('CanUnitDocRefNum');
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
        Schema::dropIfExists('cannibalized_units');
    }
};

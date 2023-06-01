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
        Schema::create('unit_brand_news', function (Blueprint $table) {
            $table->id();
            $table->string('BNUnitType');
            $table->string('BNUArrivalDate');
            $table->string('BNUBrand');
            $table->string('BNUClassification');
            $table->string('BNUModel');
            $table->string('BNUSerialNum');
            $table->string('BNUCode');
            $table->string('BNUMastType');
            $table->string('BNUMastHeight');
            $table->string('BNUForkSize');
            $table->string('BNUwAttachment');
            $table->string('BNUAttType');
            $table->string('BNUAttModel');
            $table->string('BNUAttSerialNum');
            $table->string('BNUwAccesories');
            $table->string('BNUAccISite');
            $table->string('BNUAccLiftCam');
            $table->string('BNUAccRedLight');
            $table->string('BNUAccBlueLight');
            $table->string('BNUAccFireExt');
            $table->string('BNUAccStLight');
            $table->string('BNUAccOthers');
            $table->string('BNUAccOthersDetail');
            $table->string('BNUTechnician1');
            $table->string('BNUTechnician2');
            $table->string('BNUSalesman');
            $table->string('BNUCustomer');
            $table->string('BNUCustAddress');
            $table->string('BNURemarks');
            $table->string('BNUStatus');
            $table->string('BNUTransferArea');
            $table->string('BNUTransferBay');
            $table->string('BNUTransferRemarks');
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
        Schema::dropIfExists('unit_brand_news');
    }
};

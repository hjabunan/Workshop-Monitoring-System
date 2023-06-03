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
        Schema::create('unit_pull_outs', function (Blueprint $table) {
            $table->id();
            $table->string('isBrandNew');
            $table->string('POUUnitType');
            $table->string('POUArrivalDate');
            $table->string('POUBrand');
            $table->string('POUClassification');
            $table->string('POUModel');
            $table->string('POUSerialNum');
            $table->string('POUCode');
            $table->string('POUMastType');
            $table->string('POUMastHeight');
            $table->string('POUForkSize');
            $table->string('POUwAttachment');
            $table->string('POUAttType');
            $table->string('POUAttModel');
            $table->string('POUAttSerialNum');
            $table->string('POUwAccesories');
            $table->string('POUAccISite');
            $table->string('POUAccLiftCam');
            $table->string('POUAccRedLight');
            $table->string('POUAccBlueLight');
            $table->string('POUAccFireExt');
            $table->string('POUAccStLight');
            $table->string('POUAccOthers');
            $table->string('POUAccOthersDetail');
            $table->string('POUTechnician1');
            $table->string('POUTechnician2');
            $table->string('POUSalesman');
            $table->string('POUCustomer');
            $table->string('POUCustAddress');
            $table->string('POURemarks');
            $table->string('POUStatus');
            $table->string('POUTransferArea');
            $table->string('POUTransferBay');
            $table->string('POUTransferRemarks');
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
        Schema::dropIfExists('unit_pull_outs');
    }
};

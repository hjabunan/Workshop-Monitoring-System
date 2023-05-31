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
        Schema::create('confirm_units', function (Blueprint $table) {
            $table->id();
            $table->string('POUID');
            $table->string('CUDate');
            $table->string('CUCustomer');
            $table->string('CUCustAddress');
            $table->string('CUBrand');
            $table->string('CUArea');
            $table->string('CUBay');
            $table->string('CUCode');
            $table->string('CUModel');
            $table->string('CUSerialNum');
            $table->string('CUMastHeight');
            $table->string('CURemarks');
            $table->tinyInteger('CUClass');
            $table->string('CUStatus');
            $table->string('CUGUStatus');
            $table->tinyInteger('CUTransferTo')->default(0);
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
        Schema::dropIfExists('confirm_units');
    }
};

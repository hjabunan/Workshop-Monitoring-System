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
        Schema::create('d_r_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('DRMonStatus');
            $table->string('DRMonDate');
            $table->string('DRMonCustomer');
            $table->string('DRMonCustAddress');
            $table->string('DRMonSupplier');
            $table->string('DRMonPRNum');
            $table->string('LDRMonCode');
            $table->string('LDRMonModel');
            $table->string('LDRMonSerial');
            $table->string('LDRMonDRNum');
            $table->string('LDRMonPUDate');
            $table->string('LDRMonReqBy');
            $table->string('RDRMonQNum');
            $table->string('RDRMonQDate');
            $table->string('RDRMonBSNum');
            $table->string('RDRMonDRNum');
            $table->string('RDRMonRetDate');
            $table->string('RDRMonRecBy');
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
        Schema::dropIfExists('d_r_monitorings');
    }
};

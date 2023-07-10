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
        Schema::create('unit_parts', function (Blueprint $table) {
            $table->id();
            $table->string('PIJONum');
            $table->string('PIMRINum');
            $table->string('PIPartNum');
            $table->string('PIDescription');
            $table->string('PIPrice');
            $table->string('PIQuantity');
            $table->string('PIDateReq');
            $table->string('PIDateRec');
            $table->string('PIReason');
            $table->string('PIDateInstalled');
            $table->string('PIRemarks');
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
        Schema::dropIfExists('unit_parts');
    }
};

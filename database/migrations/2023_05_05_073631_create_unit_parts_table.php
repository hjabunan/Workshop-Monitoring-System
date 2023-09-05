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
            $table->bigInteger('PIPartID');
            $table->string('PIPartNum')->nullable();
            $table->string('PIDescription')->nullable();
            $table->string('PIPrice')->nullable();
            $table->string('PIQuantity');
            $table->string('PIDateReq');
            $table->string('PIDateRec');
            $table->string('PIReason');
            $table->string('PIDateInstalled')->nullable();
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

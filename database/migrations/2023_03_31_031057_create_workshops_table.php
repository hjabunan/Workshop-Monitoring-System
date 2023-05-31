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
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('WSCUID');
            $table->string('WSBayNum');
            $table->string('WSCompName');
            $table->string('WSBrand');
            $table->string('WSModel');
            $table->string('WSCode');
            $table->string('WSSerialNum');
            $table->string('WSMastType');
            $table->string('WSActivity');
            $table->string('WSDateStarted');
            $table->string('WSTargetDate');
            $table->string('WSPIC');
            $table->string('MonToA');
            $table->string('MonStatus');
            $table->string('MonUnitType');
            $table->string('MonPPlanDS');
            $table->string('MonPPlanDT');
            $table->string('MonPActualDA');
            $table->string('MonPActualDE');
            $table->string('MonATIDS');
            $table->string('MonATIDE');
            $table->string('MonATRDS');
            $table->string('MonATRDE');
            $table->string('MonAAIDS');
            $table->string('MonAAIDE');
            $table->string('MonAARDS');
            $table->string('MonAARDE');
            $table->string('MonRemarks');
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
        Schema::dropIfExists('workshops');
    }
};

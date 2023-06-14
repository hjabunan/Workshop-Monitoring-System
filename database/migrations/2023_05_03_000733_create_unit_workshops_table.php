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
        Schema::create('unit_workshops', function (Blueprint $table) {
            $table->id();
            $table->string('isBrandNew');
            $table->string('WSPOUID');
            $table->string('WSBayNum');
            $table->string('WSToA');
            $table->string('WSStatus');
            // $table->string('WHStatus');
            $table->string('WSUnitType');
            // $table->string('WSPPlanDS');
            // $table->string('WSPPlanDT');
            // $table->string('WSPActualDA');
            // $table->string('WSPActualDE');
            $table->string('WSATIDS');
            $table->string('WSATIDE');
            $table->string('WSATRDS');
            $table->string('WSATRDE');
            $table->string('WSAAIDS');
            $table->string('WSAAIDE');
            $table->string('WSAARDS');
            $table->string('WSAARDE');
            $table->string('WSRemarks');
            $table->string('WSVerifiedBy');
            $table->string('WSUnitCondition');
            $table->string('WSDelTransfer')->default(0);
            // $table->string('WS');
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
        Schema::dropIfExists('unit_workshops');
    }
};

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
        Schema::create('d_r_parts', function (Blueprint $table) {
            $table->id();
            $table->string('DRPartDate');
            $table->string('DRPartMonID');
            $table->string('DRPartPartNum');
            $table->string('DRPartDescription');
            $table->string('DRPartQuantity');
            $table->string('DRPartPurpose');
            $table->string('DRPartRemarks');
            $table->string('DRPartStatus');
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
        Schema::dropIfExists('d_r_parts');
    }
};

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
        Schema::create('cannibalized_parts', function (Blueprint $table) {
            $table->id();
            $table->string('CanPartDate');
            $table->string('CanPartCUID');
            $table->string('CanPartPartNum');
            $table->string('CanPartDescription');
            $table->string('CanPartQuantity');
            $table->string('CanPartRemarks');
            $table->string('CanPartStatus');
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
        Schema::dropIfExists('cannibalized_parts');
    }
};

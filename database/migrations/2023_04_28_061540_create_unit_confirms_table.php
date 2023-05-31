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
        Schema::create('unit_confirms', function (Blueprint $table) {
            $table->id();
            $table->string('POUID');
            $table->string('CUTransferDate');
            $table->string('CUTransferRemarks');
            $table->string('CUTransferStatus');
            $table->string('CUTransferArea');
            $table->string('CUTransferBay');
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
        Schema::dropIfExists('unit_confirms');
    }
};

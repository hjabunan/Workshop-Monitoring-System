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
        Schema::create('pull_out_units', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('POUClass');
            $table->string('POUDate');
            $table->string('POUCustomer');
            $table->string('POUCustAddress');
            $table->string('POUBrand');
            $table->string('POUCode');
            $table->string('POUModel');
            $table->string('POUSerialNum');
            $table->string('POUMastHeight');
            $table->string('POURemarks');
            // $table->string('POUArea');
            // $table->string('POUBay');
            $table->string('POUStatus')->default(0);
            // $table->string('POUGUStatus')->default(0);
            $table->string('POUTransferTo')->default(0);
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
        Schema::dropIfExists('pull_out_units');
    }
};

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
        Schema::create('b_t_reports', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('status');
            $table->string('bay');
            $table->string('code');
            $table->string('model');
            $table->string('serial');
            $table->string('height');
            $table->string('remarks');
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
        Schema::dropIfExists('b_t_reports');
    }
};

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
        Schema::create('technician_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('techid');
            $table->string('baynum');
            $table->string('JONumber');
            $table->string('POUID');
            $table->string('scheddate');
            $table->string('scopeofwork');
            $table->string('activity');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('technician_schedules');
    }
};

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
        Schema::create('area_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('top');
            $table->string('left');
            $table->string('height');
            $table->string('width');
            $table->string('width_ratio');
            $table->string('height_ratio');
            $table->string('left_ratio');
            $table->string('hexcolor');
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
        Schema::dropIfExists('area_tables');
    }
};

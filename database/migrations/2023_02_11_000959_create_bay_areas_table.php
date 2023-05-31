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
        Schema::create('bay_areas', function (Blueprint $table) {
            $table->id();
            $table->string('area_name');
            $table->string('section');
            $table->string('category');
            $table->string('status')->default('1');
            // $table->boolean('is_Active');
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
        Schema::dropIfExists('bay_areas');
    }
};

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
        Schema::create('stagings', function (Blueprint $table) {
            $table->id();
            $table->string('stg_name');
            $table->string('stg_dayin');
            $table->string('stg_dayout');
            $table->string('stg_color');

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
        Schema::dropIfExists('stagings');
    }
};

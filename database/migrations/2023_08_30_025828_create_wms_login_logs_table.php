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
        Schema::create('wms_login_logs', function (Blueprint $table) {
            $table->id();
            $table->string('login_username');
            $table->string('login_time');
            $table->string('login_ipaddress');
            $table->string('login_eventtype');
            $table->string('login_status');
            $table->string('failure_reason');
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
        Schema::dropIfExists('wms_login_logs');
    }
};

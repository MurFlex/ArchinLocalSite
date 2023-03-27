<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicableDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicable_devices', function (Blueprint $table) {
            $table->id('device_id');
            $table->integer('company_id')->nullable();
            $table->text('certNum')->nullable();
            $table->string('signPass')->nullable();
            $table->string('signMi')->nullable();
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
        Schema::dropIfExists('applicable_devices');
    }
}

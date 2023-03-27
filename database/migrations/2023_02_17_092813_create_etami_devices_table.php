<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtamiDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etami_devices', function (Blueprint $table) {
            $table->id('device_id');
            $table->string('regNumber');
            $table->string('mitypeNumber');
            $table->string('mitypeURL')->nullable();
            $table->string('mitypeTitle');
            $table->text('mitypeType')->nullable();
            $table->string('modification')->nullable();
            $table->string('manufactureNum')->nullable();
            $table->string('manufactureYear')->nullable();
            $table->string('rankCode')->nullable();
            $table->string('rankTitle')->nullable();
            $table->string('schemaTitle')->nullable();
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
        Schema::dropIfExists('etami_devices');
    }
}

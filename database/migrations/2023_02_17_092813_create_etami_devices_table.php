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
            $table->string('mitypeURL');
            $table->string('mitypeTitle');
            $table->string('mitypeType');
            $table->string('modification');
            $table->string('manufactureNum');
            $table->string('manufactureYear');
            $table->string('rankCode');
            $table->string('rankTitle');
            $table->string('schemaTitle');
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

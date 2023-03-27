<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinglemiDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('singlemi_devices', function (Blueprint $table) {
            $table->id('device_id');
            $table->string('mitypeNumber');
            $table->text('mitypeURL');
            $table->text('mitypeType');
            $table->text('mitypeTitle');
            $table->string('manufactureNum')->nullable();
            $table->string('inventoryNum')->nullable();
            $table->string('manufactureYear')->nullable();
            $table->string('modification')->nullable();
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
        Schema::dropIfExists('singlemi_devices');
    }
}

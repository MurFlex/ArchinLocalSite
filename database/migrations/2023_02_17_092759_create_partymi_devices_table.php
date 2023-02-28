<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartymiDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partymi_devices', function (Blueprint $table) {
            $table->id('device_id');
            $table->string('mitypeNumber')->nullable();
            $table->string('mitypeURL')->nullable();
            $table->string('mitypeTitle')->nullable();
            $table->string('mitypeType')->nullable();
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('partymi_devices');
    }
}

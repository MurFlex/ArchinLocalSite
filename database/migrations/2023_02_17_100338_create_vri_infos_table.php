<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVriInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vri_infos', function (Blueprint $table) {
            $table->id('device_id');
            $table->string('organization');
            $table->string('signCipher');
            $table->string('miOwner');
            $table->string('vrfDate');
            $table->string('validDate');
            $table->string('vriType');
            $table->string('docTitle');
            $table->string('applicable');
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
        Schema::dropIfExists('vri_infos');
    }
}

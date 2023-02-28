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
            $table->text('organization')->nullable();
            $table->string('signCipher')->nullable();
            $table->text('miOwner')->nullable();
            $table->string('vrfDate')->nullable();
            $table->string('validDate')->nullable();
            $table->string('vriType')->nullable();
            $table->string('docTitle')->nullable();
            $table->string('applicable')->nullable();
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

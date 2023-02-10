<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtaMISTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eta_m_i_s', function (Blueprint $table) {
            $table->id('device_id');
            $table->string('regNumber');
            $table->string('mitypeNumber');
            $table->string('mitypeURL');
            $table->string('mitypeTile');
            $table->string('mitypeType');
            $table->string('modification');
            $table->string('manufactureNum');
            $table->string('manufactureYear');
            $table->string('rankCode');
            $table->string('rankTitle');
            $table->string('schemaTitle');
            $table->string('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eta_m_i_s');
    }
}

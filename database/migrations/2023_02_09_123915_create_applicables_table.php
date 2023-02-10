<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicables', function (Blueprint $table) {
            $table->id('device_id');
            $table->string('certNum');
            $table->string('stickerNum');
            $table->string('signPass');
            $table->string('signMi');
            $table->string('noticeNum');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicables');
    }
}

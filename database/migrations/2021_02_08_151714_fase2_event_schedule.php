<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2EventSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xin_event_schedule', function (Blueprint $table) {
            $table->bigIncrements('schedule_id');
            $table->integer('event_id');
            $table->dateTime('schedule_start');
            $table->dateTime('schedule_end')->nullable();
            $table->string('icon')->nullable();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->string('link')->nullable();
            $table->string('additional_information')->nullable();
            $table->dateTime('deleted_at')->nullable();
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
        //
    }
}

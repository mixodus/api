<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2EventParticipantStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xin_event_participant_status', function (Blueprint $table) {
            $table->bigIncrements('status_id');
            $table->integer('employee_id');
            $table->integer('schedule_id');
			$table->enum('status',["Pending","Failed","Passed"])->default("Pending");
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

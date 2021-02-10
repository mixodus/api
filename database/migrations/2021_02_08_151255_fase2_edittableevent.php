<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2Edittableevent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xin_events', function($table) {
            $table->text('event_requirement')->nullable()->after('event_note');
            $table->text('event_additional_information')->nullable()->after('event_requirement');
            $table->text('event_prize')->nullable()->after('event_speaker');
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

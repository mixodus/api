<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2EventUpdateParticipantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            ALTER TABLE xin_events_participant
            CHANGE COLUMN created_at created_at DATETIME NOT NULL AFTER country,
            CHANGE COLUMN modified_at modified_at DATETIME NOT NULL AFTER updated_at;
        ");

        Schema::table('xin_events_participant', function($table) {
            $table->string('university')->nullable()->after('country');
            $table->string('major')->nullable()->after('university');
            $table->integer('semester')->nullable()->after('major');
            $table->string('idcard_file')->nullable()->after('semester');
            $table->string('studentcard_file')->nullable()->after('idcard_file');
            $table->string('transcripts_file')->nullable()->after('studentcard_file');
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

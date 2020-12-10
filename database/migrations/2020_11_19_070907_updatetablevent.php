<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updatetablevent extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('xin_events', function($table) {
			$table->timestamp('updated_at')->nullable()->after('created_at');
		});
		DB::unprepared("
			ALTER TABLE xin_events MODIFY event_note TEXT NULL;
			ALTER TABLE xin_events MODIFY event_speaker VARCHAR(100) NULL;
		");
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

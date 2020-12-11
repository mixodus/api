<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updatetableemployeexperienceproject extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('xin_challenge', function($table) {
			$table->timestamp('updated_at')->nullable()->after('created_at');
		});
		DB::unprepared("
			ALTER TABLE xin_challenge MODIFY challenge_description TEXT NULL;
			ALTER TABLE xin_challenge MODIFY challenge_long_desciption TEXT NULL;
			ALTER TABLE xin_challenge MODIFY challenge_icon_trophy varchar(150) NULL;
			ALTER TABLE xin_challenge MODIFY challenge_title_trophy varchar(100) NULL;
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

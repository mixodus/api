<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updatetablejobs extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('xin_jobs', function($table) {
			$table->timestamp('updated_at')->nullable()->after('created_at');
			$table->timestamp('deleted_at')->nullable()->after('updated_at');
		});
		DB::unprepared("
			ALTER TABLE xin_jobs MODIFY minimum_experience varchar(150) NULL;
			ALTER TABLE xin_jobs MODIFY short_description mediumtext NULL;
			ALTER TABLE xin_jobs MODIFY long_description mediumtext NULL;
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

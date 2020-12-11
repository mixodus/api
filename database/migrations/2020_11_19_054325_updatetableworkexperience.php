<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updatetableworkexperience extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('xin_employee_work_experience', function($table) {
			$table->timestamp('updated_at')->nullable()->after('created_at');
		});
		DB::unprepared("
			ALTER TABLE xin_employee_work_experience MODIFY description TEXT NULL;
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

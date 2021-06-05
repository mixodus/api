<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updatetableemployecertification extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('xin_employee_certification', function($table) {
			$table->timestamp('updated_at')->nullable()->after('created_at');
		});
		DB::unprepared("
			ALTER TABLE xin_employee_certification MODIFY certification_file TEXT NULL;
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

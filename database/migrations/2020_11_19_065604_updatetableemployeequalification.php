<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updatetableemployeequalification extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('xin_employee_qualification', function($table) {
			$table->timestamp('updated_at')->nullable()->after('created_at');
		});
		DB::unprepared("
			ALTER TABLE xin_employee_qualification MODIFY description TEXT NULL;
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

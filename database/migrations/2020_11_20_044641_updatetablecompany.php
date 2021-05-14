<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updatetablecompany extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('xin_companies', function($table) {
			$table->timestamp('updated_at')->nullable()->after('created_at');
			$table->timestamp('deleted_at')->nullable()->after('updated_at');
		});
		DB::unprepared("
			ALTER TABLE xin_companies MODIFY contact_number varchar(150) NULL;
			ALTER TABLE xin_companies MODIFY website_url varchar(255) NULL;
			ALTER TABLE xin_companies MODIFY address_2 mediumtext NULL;
			ALTER TABLE xin_companies MODIFY government_tax varchar(255) NULL;
			ALTER TABLE xin_companies MODIFY registration_no varchar(255) NULL;
			ALTER TABLE xin_companies MODIFY registration_no varchar(255) NULL;
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2UpdateXinJobsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::unprepared("
			ALTER TABLE xin_currencies MODIFY company_id int(11) NULL;
			ALTER TABLE xin_currencies MODIFY symbol varchar(50) NULL;
			INSERT INTO `xin_currencies` (`currency_id`, `company_id`, `name`, `code`, `symbol`) VALUES
			(2, NULL, 'Rupiah', 'Rp', NULL);
		");
		
		Schema::table('xin_jobs', function($table) {
			$table->integer('currency_id')->after('country')->default(2);
			$table->integer('salary_start')->nullable();
			$table->integer('salary_end')->nullable();
			$table->string('salary_desc')->nullable();
			$table->enum('show_salary',[0,1])->default(0);
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

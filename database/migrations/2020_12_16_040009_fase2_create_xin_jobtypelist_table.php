<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2CreateXinJobtypelistTable extends Migration
{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('xin_jobtypelist', function (Blueprint $table) {
				$table->bigIncrements('job_type_list_id');
				$table->integer('job_id');
				$table->integer('type_id');
				$table->dateTime('deleted_at')->nullable();
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
				Schema::dropIfExists('xin_jobtypelist');
		}
}

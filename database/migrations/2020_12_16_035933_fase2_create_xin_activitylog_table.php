<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2CreateXinActivitylogTable extends Migration
{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('xin_activity_log', function (Blueprint $table) {
				$table->bigIncrements('log_id');
				$table->string('endpoint');
				$table->enum('version',[1,2])->default(1);
				$table->string('request');
				$table->integer('user_id')->nullable();
				$table->string('header')->nullable();
				$table->string('result');
				$table->string('status');
				$table->string('ip_address')->nullable();
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
				Schema::dropIfExists('xin_activity_log');
		}
}

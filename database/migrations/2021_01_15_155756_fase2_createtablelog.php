<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2Createtablelog extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('xin_log', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->enum('server_type',["local","development","production"])->default("development");
			$table->enum('type',["get-data","action","remove-data"])->default("get-data");
			$table->string('name');
			$table->integer('user_id')->default(0);
			$table->string('version')->default("0");
			$table->string('ip_address')->default("0");
			$table->string('method')->default("get");
			$table->string('request_header');
			$table->string('request_body');
			$table->string('response');
			$table->string('status_code');
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
		//
	}
}

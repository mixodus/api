<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Newscommenttable extends Migration
{
	/**
	 * Fase 2   
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('xin_news_comment', function (Blueprint $table) {
			$table->bigIncrements('comment_id');
			$table->integer('news_id');
			$table->integer('user_id');
			$table->string('comment');
			$table->string('desc')->nullable();
			$table->string('attachment')->nullable();
			$table->dateTime('deleted_at')->nullable();
			$table->enum('status', [0, 1])->comment('0 active | 1 banned')->default(0);
			$table->timestamps();
		});
		Schema::create('xin_news_comment_reply', function (Blueprint $table) {
			$table->bigIncrements('reply_id');
			$table->integer('comment_id');
			$table->integer('comment_by');
			$table->integer('reply_by');
			$table->string('comment');
			$table->string('desc')->nullable();
			$table->string('attachment')->nullable();
			$table->dateTime('deleted_at')->nullable();
			$table->enum('status', [0, 1])->comment('0 active | 1 banned')->default(0);
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
		Schema::dropIfExists('xin_news_comment');
		Schema::dropIfExists('xin_news_comment_reply');
	}
}

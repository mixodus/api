<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2UpdateTablelogv2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE xin_log;");
        Schema::create('xin_log', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->enum('server_type',["local","development","production"])->default("development")->nullable();
			$table->enum('type',["get-data","action","remove-data"])->default("get-data")->nullable();
			$table->enum('module',["user","user-data","challenge","event","general","news","jobs","notif","point","referral"])->default("general")->nullable();
			$table->string('name')->nullable();
            $table->string('uri')->nullable();
			$table->integer('user_id')->default(0);
			$table->string('version')->default("0")->nullable();
			$table->string('ip_address')->default("0")->nullable();
			$table->string('method')->default("get")->nullable();
			$table->longText('request_header')->nullable();
			$table->longText('request_body')->nullable();
			$table->longText('response')->nullable();
			$table->string('status_code')->nullable();
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

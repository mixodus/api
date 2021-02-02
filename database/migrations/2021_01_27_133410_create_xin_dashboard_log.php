<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXinDashboardLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xin_dashboard_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('server_type',["local","development","production"])->default("development")->nullable();
            $table->enum('type',["get-data","action","remove-data"])->default("get-data")->nullable();
            $table->enum('module',["admin","employee","challenge","event","role","news","jobs","level","comment","general"])->default("general")->nullable();
            $table->string('name')->nullable();
            $table->string('uri')->nullable();
            $table->integer('user_id')->default(0);
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
        Schema::drop('xin_dashboard_log');
    }
}

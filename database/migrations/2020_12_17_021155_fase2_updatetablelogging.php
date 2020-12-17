<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2Updatetablelogging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE xin_activity_log;");
        
        Schema::create('xin_activity_log', function (Blueprint $table) {
            $table->bigIncrements('log_id');
            $table->enum('version',[1,2])->default(1);
            $table->string('url');
            $table->string('ip_address')->nullable();
            $table->string('method');
            $table->string('status_code');
            $table->string('request_body')->nullable();
            $table->string('response')->nullable();
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
        //
    }
}

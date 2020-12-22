<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2Tablejobsupdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            alter table xin_jobs drop column salary_start;
            alter table xin_jobs drop column salary_end; 
		");
		
		Schema::table('xin_jobs', function($table) {
			$table->integer('salary_start')->default(0);
			$table->integer('salary_end')->default(0);
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

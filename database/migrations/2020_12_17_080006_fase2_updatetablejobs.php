<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2Updatetablejobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
			alter table xin_jobs drop column province
		");
        Schema::table('xin_jobs', function($table) {
			$table->integer('province')->default(31)->nullable()->after('country');
			$table->integer('city_id')->default(3174)->nullable()->after('province');
			$table->integer('districts_id')->default(0)->nullable()->after('city_id');
			$table->integer('subdistrict_id')->default(0)->nullable()->after('districts_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}

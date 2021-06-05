<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReferralColumnUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xin_referral', function($table) {
            $table->string('job_position')->after('file')->nullable();
            $table->string('fee')->after('file')->nullable();
            $table->enum('source', ["mobile","web"])->after('referral_id')->nullable()->default("mobile");
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

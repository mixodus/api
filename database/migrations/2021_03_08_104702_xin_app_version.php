<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XinAppVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xin_app_version', function (Blueprint $table) {
            $table->bigIncrements('app_version_id');
            $table->string('version');
            $table->string('url_update');
            $table->enum('is_force',[0,1])->default(0)->comment("0=false force | 1= true force");
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

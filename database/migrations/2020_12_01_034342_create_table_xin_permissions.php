<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableXinPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xin_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('group_name')->nullable();
            $table->integer('menu_id')->nullable();
            $table->string('action')->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xin_permissions');
    }
}

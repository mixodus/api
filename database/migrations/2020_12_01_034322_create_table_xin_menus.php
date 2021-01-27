<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableXinMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xin_menus', function (Blueprint $table) {
            $class = ['admin', 'hrd', 'finance'];
            $table->increments('id');
            $table->string('name', 100);
            $table->string('icon', 100);
            $table->string('url', 200);
            $table->string('type')->nullable();
            $table->integer('is_parent')->default(0);
            $table->integer('parent_id')->default(0);
            $table->integer('level')->default(0);
            $table->string('group_name', 50);
            $table->string('initial')->nullable();
            $table->enum('class', $class)->nullable();
            $table->integer('status')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xin_menus');
    }
}

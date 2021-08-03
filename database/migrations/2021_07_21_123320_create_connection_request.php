<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConnectionRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connection_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('source_id');
            $table->bigInteger('target_id');
            $table->enum('status', ['pending','accepted', 'blocked', 'idle_state'])->default('pending');
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

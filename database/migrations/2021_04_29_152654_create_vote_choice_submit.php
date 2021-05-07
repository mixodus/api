<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoteChoiceSubmit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_choice_submit', function (Blueprint $table) {
            $table->bigIncrements('submit_id');
            $table->unsignedBigInteger('vote_topic_id');
            $table->unsignedBigInteger('vote_choice_id');
            $table->Integer('employee_id');
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
        Schema::dropIfExists('vote_choice_submit');
    }
}

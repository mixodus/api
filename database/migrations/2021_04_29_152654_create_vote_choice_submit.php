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
            $table->bigIncrements('vote_choice_submit_id');
            $table->integer('vote_themes_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('vote_choice_id')->nullable();
            $table->boolean('vote_status')->default(1);
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

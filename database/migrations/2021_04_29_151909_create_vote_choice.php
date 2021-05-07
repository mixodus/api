<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoteChoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_choices', function (Blueprint $table) {
            $table->bigIncrements('choice_id');
            $table->unsignedBigInteger('vote_topic_id');
            $table->string('name', 100)->nullable();
            $table->string('icon', 100)->nullable();
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
        Schema::dropIfExists('vote_choice');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirdropSubmitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airdrop_submits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('airdrop_id');
            $table->string('twitter_username');
            $table->string('telegram_username');
            $table->string('wallet');
            $table->integer('status');
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
        Schema::dropIfExists('airdrop_submits');
    }
}

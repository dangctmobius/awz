<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTagRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_tag_relationships', function (Blueprint $table) {
            $table->id();
            $table->integer("post_id")->unsigned();
            $table->integer("tag_id")->unsigned();
            $table->integer("user_id")->unsigned();
            $table->integer("likes")->default(0);
            $table->integer("dislikes")->default(0);
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
        Schema::dropIfExists('post_tag_relationships');
    }
}

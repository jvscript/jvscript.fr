<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->increments('user_id');
            $table->string('comment');
            $table->integer('commentable_id');
            $table->string('commentable_type ');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');
            $table->increments('user_id');
            $table->boolean('liked');
            $table->integer('likable_id');
            $table->string('likable_type ');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('likes');
        Schema::dropIfExists('comments');
    }

}

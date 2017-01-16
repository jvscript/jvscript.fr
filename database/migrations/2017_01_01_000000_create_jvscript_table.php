<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJvscriptTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('scripts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->nullable()->unique();
            $table->string('autor')->nullable();
            $table->string('user_id')->nullable()->comment("pour attribuer le script à un utilisateurs de jvscript");
            $table->string('user_email')->nullable()->comment("pour notifier par email");
            $table->text('description')->nullable();
            $table->integer('note')->default(0);
            $table->integer('note_count')->default(0);
            $table->integer('install_count')->default(0);
            $table->string('js_url');
            $table->string('repo_url')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('don_url')->nullable();
            $table->integer('status')->default(0)->comment("0 awaiting validation / 1 validated / 2 refused");
            $table->integer('sensibility')->default(0)->comment("0 Clean / 1 Warning / 2 Danger");
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->integer('tag_id');
            $table->integer('taggable_id');
            $table->string('taggable_type');
        });
        /**
         * _TODO : relation morph sur les tags 
         */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('scripts');
    }

}

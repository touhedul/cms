<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('p_title',250)->nullable();
            $table->string('p_name',50)->nullable();
            $table->binary('p_content');
            $table->string('m_title',250)->nullable();
            $table->text('m_description')->nullable();
            $table->text('m_keywords')->nullable();
            $table->string('m_url',250)->nullable();
            $table->string('template',50)->default('default')->nullable();
            $table->boolean('visibility')->nullable()->default(1);
            $table->string('header_media_path')->nullable();
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
        Schema::dropIfExists('pages');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('label', 100)->nullable();
            $table->string('filename', 100)->nullable();
            $table->string('extension', 20)->nullable();
            $table->string('size', 20)->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('owner_id')->nullable();
            $table->string('owner_type', 50)->nullable();
            $table->boolean('amendment')->default(0);
            $table->string('path', 250)->nullable();
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
        Schema::dropIfExists('documents');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('owner_type');
            $table->Integer('owner_id')->default(0);
            $table->string('type')->default('image')->nullable();
            $table->string('label')->default('')->nullable();
            $table->string('description')->default('')->nullable();
            $table->string('path');
            $table->string('thumb_path')->nullable();
            $table->timestamps(); 
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        return;
        Schema::create('test', function (Blueprint $table) {
            $table->id();
            $table->string('text')->nullable();
            $table->string('alias')->nullable();
            $table->string('password')->nullable();
            $table->text('textarea')->nullable();
            $table->text('ckeditor')->nullable();
            $table->string('coords')->nullable();
            $table->string('file')->nullable();
            $table->string('image')->nullable();
            $table->text('media_picker')->nullable();
            $table->text('list')->nullable();
            $table->text('array_builder')->nullable();
            $table->text('gallery')->nullable();
            $table->string('select')->nullable();
            $table->string('color')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->boolean('checkbox')->nullable()->default(false);
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('test');
    }
};

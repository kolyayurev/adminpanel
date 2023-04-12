<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations (СЕО - ЧПУ).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sef', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->text('get_params')->nullable();
            $table->string('alias');
            $table->smallInteger('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sef');
    }
};

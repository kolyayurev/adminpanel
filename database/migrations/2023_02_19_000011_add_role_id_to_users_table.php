<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table(config('adminpanel.users_table', 'users'), function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned()->nullable()->after('id');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {

        $usersTable = config('adminpanel.users_table', 'users');
        if (Schema::hasColumn($usersTable, 'role_id')) {
            Schema::table($usersTable, function (Blueprint $table) {
                $table->dropColumn('role_id');
            });
        }
    }
};

<?php

use KY\AdminPanel\Traits\Seedable;
use Illuminate\Database\Seeder;

class AdminPanelDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed('RolesTableSeeder');
        $this->seed('SettingsTableSeeder');
        $this->seed('UsersTableSeeder');
    }

}

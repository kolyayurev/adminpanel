<?php

use Illuminate\Database\Seeder;
use KY\AdminPanel\Traits\Seedable;

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

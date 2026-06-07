<?php

use Illuminate\Database\Seeder;
use KY\AdminPanel\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $setting = $this->findSetting('admin.icon');
        if (! $setting->exists) {
            $setting->fill([
                'display_name' => __('adminpanel::seeders.settings.admin.icon'),
                'value' => '',
                'details' => '',
            ])->save();
        }
    }

    protected function findSetting(string $key)
    {
        return Setting::firstOrNew(['key' => $key]);
    }
}

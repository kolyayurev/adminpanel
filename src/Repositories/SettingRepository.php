<?php

namespace KY\AdminPanel\Repositories;

use AdminPanel;

class SettingRepository extends BaseRepository
{
    public function modelClass(): string
    {
        return AdminPanel::modelClass('Setting');
    }
}

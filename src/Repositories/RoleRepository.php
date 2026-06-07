<?php

namespace KY\AdminPanel\Repositories;

use AdminPanel;

class RoleRepository extends BaseRepository
{
    public function modelClass(): string
    {
        return AdminPanel::modelClass('Role');
    }
}

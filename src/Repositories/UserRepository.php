<?php

namespace KY\AdminPanel\Repositories;

use AdminPanel;

class UserRepository extends BaseRepository
{
    public function modelClass(): string
    {
        return AdminPanel::modelClass('User');
    }
}

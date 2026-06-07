<?php

namespace KY\AdminPanel\Repositories;

use AdminPanel;

class RedirectRepository extends BaseRepository
{
    public function modelClass(): string
    {
        return AdminPanel::modelClass('Redirect');
    }
}

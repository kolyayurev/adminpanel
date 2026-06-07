<?php

namespace KY\AdminPanel\Repositories;

use AdminPanel;

class SefRepository extends BaseRepository
{
    public function modelClass(): string
    {
        return AdminPanel::modelClass('Sef');
    }
}

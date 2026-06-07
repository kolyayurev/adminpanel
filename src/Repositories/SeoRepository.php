<?php

namespace KY\AdminPanel\Repositories;

use AdminPanel;

class SeoRepository extends BaseRepository
{
    public function modelClass(): string
    {
        return AdminPanel::modelClass('Seo');
    }
}

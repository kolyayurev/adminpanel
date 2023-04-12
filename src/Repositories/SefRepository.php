<?php

namespace KY\AdminPanel\Repositories;

use AdminPanel;
use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\DataTypeContract;


class SefRepository extends BaseRepository
{
    public function modelClass():string
    {
        return AdminPanel::modelClass('Sef');
    }

}

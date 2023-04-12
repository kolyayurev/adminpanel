<?php

namespace KY\AdminPanel\Models;

use Illuminate\Database\Eloquent\Model;
use KY\AdminPanel\Facades\AdminPanel;

class Role extends Model
{
    protected $guarded = [];

    public function users()
    {
        $userModel = AdminPanel::modelClass('User');

        return $this->belongsToMany($userModel, 'user_role')
                    ->select(app($userModel)->getTable().'.*')
                    ->union($this->hasMany($userModel))->getQuery();
    }

    public function permissions()
    {
        return $this->belongsToMany(AdminPanel::modelClass('Permission'));
    }
}

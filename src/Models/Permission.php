<?php

namespace KY\AdminPanel\Models;

use Str;

use Illuminate\Database\Eloquent\Model;
use KY\AdminPanel\Facades\AdminPanel;

class Permission extends Model
{
    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(AdminPanel::modelClass('Role'));
    }

    public static function check($key)
    {
       return self::where('key',$key)->exists();
    }

}

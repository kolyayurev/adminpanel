<?php

namespace KY\AdminPanel\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * scopes
     */
    public function scopeVisible($query)
    {
        return $query->where('visible',true);
    }

}

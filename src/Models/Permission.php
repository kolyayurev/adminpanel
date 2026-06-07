<?php

namespace KY\AdminPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use KY\AdminPanel\Database\Factories\PermissionFactory;
use KY\AdminPanel\Facades\AdminPanel;

/**
 * @property int $id
 * @property string $key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static PermissionFactory factory($count = null, $state = [])
 */
class Permission extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(AdminPanel::modelClass('Role'));
    }

    public static function check($key)
    {
       return self::where('key',$key)->exists();
    }

}

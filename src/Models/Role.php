<?php

namespace KY\AdminPanel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use KY\AdminPanel\Database\Factories\RoleFactory;
use KY\AdminPanel\Facades\AdminPanel;

/**
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static RoleFactory factory($count = null, $state = [])
 */
class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users(): Builder
    {
        $userModel = AdminPanel::modelClass('User');

        return $this->belongsToMany($userModel, 'user_role')
            ->select(app($userModel)->getTable().'.*')
            ->union($this->hasMany($userModel))->getQuery();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(AdminPanel::modelClass('Permission'));
    }
}

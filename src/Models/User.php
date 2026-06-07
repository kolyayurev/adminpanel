<?php

namespace KY\AdminPanel\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use KY\AdminPanel\Contracts\UserContract;
use KY\AdminPanel\Database\Factories\UserFactory;
use KY\AdminPanel\Traits\AdminPanelUser;

/**
 * @property int $id
 * @property int|null $role_id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static UserFactory factory($count = null, $state = [])
 */
class User extends Authenticatable implements UserContract
{
    use HasFactory;
    use AdminPanelUser;

    protected $guarded = [];

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


}

<?php

namespace KY\AdminPanel\Tests\Utils\Traits;

use KY\AdminPanel\Models\Permission;
use KY\AdminPanel\Models\Role;
use KY\AdminPanel\Models\User;

trait CreatesAdminPanelModels
{
    protected function createRole(array $attributes = []): Role
    {
        return Role::factory()->create($attributes);
    }

    protected function createAdminRole(array $attributes = []): Role
    {
        return Role::factory()->admin()->create($attributes);
    }

    protected function createUserRole(array $attributes = []): Role
    {
        return Role::factory()->user()->create($attributes);
    }

    protected function createPermission(array $attributes = []): Permission
    {
        return Permission::factory()->create($attributes);
    }

    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function createAdminUser(array $attributes = []): User
    {
        $role = $this->createAdminRole();

        return $this->createUser(array_merge([
            'role_id' => $role->id,
        ], $attributes));
    }
}

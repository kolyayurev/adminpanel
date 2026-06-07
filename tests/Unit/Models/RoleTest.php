<?php

namespace KY\AdminPanel\Tests\Unit\Models;

use KY\AdminPanel\Models\Permission;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Models\Role
 */
class RoleTest extends TestCase
{
    /**
     * @covers ::permissions
     */
    public function test_permissions_returns_belongs_to_many_relation(): void
    {
        $role = $this->createRole();
        $permission = Permission::factory()->create(['key' => 'settings.edit']);
        $role->permissions()->attach($permission);

        $this->assertTrue($permission->is($role->permissions()->first()));
    }

    /**
     * @covers ::users
     */
    public function test_users_returns_default_and_pivot_role_users(): void
    {
        $role = $this->createRole();
        $defaultRoleUser = $this->createUser(['role_id' => $role->id]);
        $pivotRoleUser = $this->createUser();
        $pivotRoleUser->roles()->attach($role);

        $users = $role->users()->get();

        $this->assertTrue($users->contains(fn ($user): bool => $user->is($defaultRoleUser)));
        $this->assertTrue($users->contains(fn ($user): bool => $user->is($pivotRoleUser)));
    }
}

<?php

namespace KY\AdminPanel\Tests\Unit\Models;

use KY\AdminPanel\Models\Permission;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Models\Permission
 */
class PermissionTest extends TestCase
{
    /**
     * @covers ::check
     */
    public function test_check_returns_true_when_permission_key_exists(): void
    {
        Permission::factory()->create(['key' => 'users.show']);

        $this->assertTrue(Permission::check('users.show'));
        $this->assertFalse(Permission::check('users.delete'));
    }

    /**
     * @covers ::roles
     */
    public function test_roles_returns_belongs_to_many_relation(): void
    {
        $role = $this->createRole();
        $permission = Permission::factory()->create(['key' => 'roles.show']);
        $permission->roles()->attach($role);

        $this->assertTrue($role->is($permission->roles()->first()));
    }
}

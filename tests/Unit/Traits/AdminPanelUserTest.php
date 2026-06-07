<?php

namespace KY\AdminPanel\Tests\Unit\Traits;

use KY\AdminPanel\Models\Permission;
use KY\AdminPanel\Tests\TestCase;
use TypeError;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\AdminPanelUser
 */
class AdminPanelUserTest extends TestCase
{
    /**
     * @covers ::role
     */
    public function test_role_returns_default_role_relation(): void
    {
        $role = $this->createRole(['name' => 'manager']);
        $user = $this->createUser(['role_id' => $role->id]);

        $this->assertTrue($role->is($user->role()->first()));
    }

    /**
     * @covers ::roles
     */
    public function test_roles_returns_alternative_roles_relation(): void
    {
        $role = $this->createRole(['name' => 'editor']);
        $user = $this->createUser();
        $user->roles()->attach($role);

        $this->assertTrue($role->is($user->roles()->first()));
    }

    /**
     * @covers ::roles_all
     */
    public function test_roles_all_merges_default_and_alternative_roles(): void
    {
        $defaultRole = $this->createRole(['name' => 'default']);
        $extraRole = $this->createRole(['name' => 'extra']);
        $user = $this->createUser(['role_id' => $defaultRole->id]);
        $user->roles()->attach($extraRole);

        $this->assertSame(['default', 'extra'], $user->roles_all()->pluck('name')->values()->all());
    }

    /**
     * @covers ::hasRole
     */
    public function test_has_role_checks_default_and_alternative_roles(): void
    {
        $defaultRole = $this->createRole(['name' => 'default']);
        $extraRole = $this->createRole(['name' => 'extra']);
        $user = $this->createUser(['role_id' => $defaultRole->id]);
        $user->roles()->attach($extraRole);

        $this->assertTrue($user->hasRole('default'));
        $this->assertTrue($user->hasRole(['missing', 'extra']));
        $this->assertFalse($user->hasRole('missing'));
    }

    /**
     * @covers ::setRole
     */
    public function test_set_role_associates_existing_role_by_name(): void
    {
        $role = $this->createRole(['name' => 'admin']);
        $user = $this->createUser();

        $result = $user->setRole('admin');

        $this->assertTrue($user->is($result));
        $this->assertSame($role->id, $user->refresh()->role_id);
    }

    /**
     * @covers ::hasPermission
     */
    public function test_has_permission_checks_permissions_from_all_roles(): void
    {
        $role = $this->createRole(['name' => 'admin']);
        $permission = Permission::factory()->create(['key' => 'dashboard.view']);
        $role->permissions()->attach($permission);
        $user = $this->createUser(['role_id' => $role->id]);

        $this->assertTrue($user->hasPermission('dashboard.view'));
        $this->assertFalse($user->hasPermission('dashboard.edit'));
    }

    /**
     * @covers ::hasPermissionOrFail
     */
    public function test_has_permission_or_fail_throws_type_error_when_permission_is_missing(): void
    {
        $this->expectException(TypeError::class);

        $this->createUser()->hasPermissionOrFail('missing.permission');
    }

    /**
     * @covers ::hasPermissionOrAbort
     */
    public function test_has_permission_or_abort_returns_true_when_permission_exists(): void
    {
        $role = $this->createRole(['name' => 'admin']);
        $permission = Permission::factory()->create(['key' => 'dashboard.view']);
        $role->permissions()->attach($permission);
        $user = $this->createUser(['role_id' => $role->id]);

        $this->assertTrue($user->hasPermissionOrAbort('dashboard.view'));
    }

    /**
     * @covers ::loadRolesRelations
     */
    public function test_load_roles_relations_loads_role_relations(): void
    {
        $role = $this->createRole(['name' => 'admin']);
        $user = $this->createUser(['role_id' => $role->id]);

        $this->callNonPublicMethod($user, 'loadRolesRelations');

        $this->assertTrue($user->relationLoaded('role'));
        $this->assertTrue($user->relationLoaded('roles'));
    }

    /**
     * @covers ::loadPermissionsRelations
     */
    public function test_load_permissions_relations_loads_permissions_on_roles(): void
    {
        $role = $this->createRole(['name' => 'admin']);
        $permission = Permission::factory()->create(['key' => 'dashboard.view']);
        $role->permissions()->attach($permission);
        $user = $this->createUser(['role_id' => $role->id]);

        $this->callNonPublicMethod($user, 'loadPermissionsRelations');

        $this->assertTrue($user->role->relationLoaded('permissions'));
    }
}

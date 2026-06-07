<?php

namespace KY\AdminPanel\Tests\Unit\Models;

use KY\AdminPanel\Models\User;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Models\User
 */
class UserTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function test_model_uses_admin_panel_user_trait_and_hidden_password_fields(): void
    {
        $user = $this->createUser([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret',
            'remember_token' => 'token',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Admin', $user->name);
        $this->assertSame('admin@example.com', $user->email);
        $this->assertArrayNotHasKey('password', $user->toArray());
        $this->assertArrayNotHasKey('remember_token', $user->toArray());
    }
}

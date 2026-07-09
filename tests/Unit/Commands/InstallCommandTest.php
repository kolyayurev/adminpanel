<?php

namespace KY\AdminPanel\Tests\Unit\Commands;

use KY\AdminPanel\Commands\InstallCommand;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Commands\InstallCommand
 */
class InstallCommandTest extends TestCase
{
    /**
     * @covers ::shouldPatchUserModel
     */
    public function test_patches_user_model_on_default_config(): void
    {
        config()->set('adminpanel.guard', null);
        config()->set('adminpanel.users_table', 'users');

        $this->assertTrue($this->callNonPublicMethod(new InstallCommand, 'shouldPatchUserModel'));
    }

    /**
     * @covers ::shouldPatchUserModel
     */
    public function test_skips_user_model_patch_on_custom_guard(): void
    {
        config()->set('adminpanel.guard', 'admin');

        $this->assertFalse($this->callNonPublicMethod(new InstallCommand, 'shouldPatchUserModel'));
    }

    /**
     * @covers ::shouldPatchUserModel
     */
    public function test_skips_user_model_patch_on_custom_users_table(): void
    {
        config()->set('adminpanel.users_table', 'admin_users');

        $this->assertFalse($this->callNonPublicMethod(new InstallCommand, 'shouldPatchUserModel'));
    }
}

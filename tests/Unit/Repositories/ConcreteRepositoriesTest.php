<?php

namespace KY\AdminPanel\Tests\Unit\Repositories;

use KY\AdminPanel\Models\Redirect;
use KY\AdminPanel\Models\Role;
use KY\AdminPanel\Models\Sef;
use KY\AdminPanel\Models\Seo;
use KY\AdminPanel\Models\Setting;
use KY\AdminPanel\Models\User;
use KY\AdminPanel\Repositories\RedirectRepository;
use KY\AdminPanel\Repositories\RoleRepository;
use KY\AdminPanel\Repositories\SefRepository;
use KY\AdminPanel\Repositories\SeoRepository;
use KY\AdminPanel\Repositories\SettingRepository;
use KY\AdminPanel\Repositories\UserRepository;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversNothing
 */
class ConcreteRepositoriesTest extends TestCase
{
    /**
     * @covers \KY\AdminPanel\Repositories\RedirectRepository::modelClass
     */
    public function test_redirect_repository_model_class_returns_redirect_model(): void
    {
        $this->assertSame(Redirect::class, (new RedirectRepository())->modelClass());
    }

    /**
     * @covers \KY\AdminPanel\Repositories\RoleRepository::modelClass
     */
    public function test_role_repository_model_class_returns_role_model(): void
    {
        $this->assertSame(Role::class, (new RoleRepository())->modelClass());
    }

    /**
     * @covers \KY\AdminPanel\Repositories\SefRepository::modelClass
     */
    public function test_sef_repository_model_class_returns_sef_model(): void
    {
        $this->assertSame(Sef::class, (new SefRepository())->modelClass());
    }

    /**
     * @covers \KY\AdminPanel\Repositories\SeoRepository::modelClass
     */
    public function test_seo_repository_model_class_returns_seo_model(): void
    {
        $this->assertSame(Seo::class, (new SeoRepository())->modelClass());
    }

    /**
     * @covers \KY\AdminPanel\Repositories\SettingRepository::modelClass
     */
    public function test_setting_repository_model_class_returns_setting_model(): void
    {
        $this->assertSame(Setting::class, (new SettingRepository())->modelClass());
    }

    /**
     * @covers \KY\AdminPanel\Repositories\UserRepository::modelClass
     */
    public function test_user_repository_model_class_returns_user_model(): void
    {
        $this->assertSame(User::class, (new UserRepository())->modelClass());
    }
}

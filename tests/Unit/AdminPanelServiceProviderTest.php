<?php

namespace KY\AdminPanel\Tests\Unit;

use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\AdminPanelServiceProvider
 */
class AdminPanelServiceProviderTest extends TestCase
{
    /**
     * @covers ::register
     */
    public function test_admin_panel_guard_defaults_to_app_default_guard(): void
    {
        config()->set('adminpanel.guard', null);
        config()->set('auth.defaults.guard', 'web');
        // Биндинг — синглтон, читает конфиг при первом резолве; сбрасываем кэш.
        $this->app->forgetInstance('AdminPanelGuard');

        $this->assertSame('web', app('AdminPanelGuard'));
    }

    /**
     * @covers ::register
     */
    public function test_admin_panel_guard_uses_configured_guard(): void
    {
        config()->set('adminpanel.guard', 'admin');
        $this->app->forgetInstance('AdminPanelGuard');

        $this->assertSame('admin', app('AdminPanelGuard'));
    }
}

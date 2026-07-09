<?php

namespace KY\AdminPanel\Tests\Unit;

use KY\AdminPanel\CustomPages\BaseCustomPage;
use KY\AdminPanel\Facades\AdminPanel;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\AdminPanel
 */
class AdminPanelCustomPagesTest extends TestCase
{
    /**
     * @covers ::addCustomPage
     * @covers ::getCustomPage
     */
    public function test_add_custom_page_registers_by_slug(): void
    {
        AdminPanel::addCustomPage(AdminPanelTestCustomPage::class);

        $this->assertInstanceOf(
            AdminPanelTestCustomPage::class,
            AdminPanel::getCustomPage('admin_panel_test')
        );
    }

    /**
     * @covers ::getCustomPage
     */
    public function test_get_custom_page_returns_null_for_unknown_slug(): void
    {
        $this->assertNull(AdminPanel::getCustomPage('missing'));
    }

    /**
     * @covers ::getCustomPages
     */
    public function test_get_custom_pages_returns_all_registered_pages(): void
    {
        AdminPanel::addCustomPage(AdminPanelTestCustomPage::class);

        $this->assertCount(1, AdminPanel::getCustomPages());
    }
}

class AdminPanelTestCustomPage extends BaseCustomPage
{
    protected string $title = 'Test';
}

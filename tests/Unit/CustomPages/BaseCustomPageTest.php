<?php

namespace KY\AdminPanel\Tests\Unit\CustomPages;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\CustomPages\BaseCustomPage;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Widgets\BaseWidget;

/**
 * @coversDefaultClass \KY\AdminPanel\CustomPages\BaseCustomPage
 */
class BaseCustomPageTest extends TestCase
{
    /**
     * @covers ::getTitle
     */
    public function test_get_title_returns_configured_title(): void
    {
        $page = new SalesCustomPage;

        $this->assertSame('Продажи', $page->getTitle());
    }

    /**
     * @covers ::getSlug
     */
    public function test_get_slug_returns_snake_case_class_name_without_custom_page_suffix(): void
    {
        $this->assertSame('sales', (new SalesCustomPage)->getSlug());
    }

    /**
     * @covers ::getSlug
     */
    public function test_get_slug_returns_explicitly_configured_slug(): void
    {
        $this->assertSame('custom_slug', (new CustomSlugCustomPage)->getSlug());
    }

    /**
     * @covers ::widgets
     * @covers ::getWidgets
     */
    public function test_widgets_are_resolvable_via_has_widgets_trait(): void
    {
        $page = new SalesCustomPage;

        $this->assertSame('revenue', $page->getWidgets()->keys()->first());
    }

    /**
     * @covers ::getIcon
     */
    public function test_get_icon_returns_default_when_not_overridden(): void
    {
        $this->assertSame('window', (new SalesCustomPage)->getIcon());
    }

    /**
     * @covers ::getIcon
     */
    public function test_get_icon_returns_overridden_value(): void
    {
        $this->assertSame('graph-up', (new CustomIconCustomPage)->getIcon());
    }

    /**
     * @covers ::showInMenu
     */
    public function test_show_in_menu_defaults_to_true(): void
    {
        $this->assertTrue((new SalesCustomPage)->showInMenu());
    }

    /**
     * @covers ::showInMenu
     */
    public function test_show_in_menu_returns_overridden_value(): void
    {
        $this->assertFalse((new HiddenFromMenuCustomPage)->showInMenu());
    }
}

class SalesCustomPage extends BaseCustomPage
{
    protected string $title = 'Продажи';

    public function widgets(): Collection
    {
        return collect([BaseCustomPageTestWidget::make('revenue')]);
    }
}

class CustomSlugCustomPage extends BaseCustomPage
{
    protected string $slug = 'custom_slug';
}

class CustomIconCustomPage extends BaseCustomPage
{
    protected string $title = 'С иконкой';

    protected string $icon = 'graph-up';
}

class HiddenFromMenuCustomPage extends BaseCustomPage
{
    protected string $title = 'Скрытая';

    public function showInMenu(): bool
    {
        return false;
    }
}

class BaseCustomPageTestWidget extends BaseWidget
{
    public function data(Request $request): array
    {
        return [];
    }
}

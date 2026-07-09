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

class BaseCustomPageTestWidget extends BaseWidget
{
    public function data(Request $request): array
    {
        return [];
    }
}

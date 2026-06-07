<?php

namespace KY\AdminPanel\Tests\Unit\DataTables\Actions;

use KY\AdminPanel\DataTables\Actions\BaseAction;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\DataTables\Actions\BaseAction
 */
class BaseActionTest extends TestCase
{
    /**
     * @covers ::setup
     */
    public function test_setup_stores_data_type_and_model(): void
    {
        $action = new BaseActionTestElement;
        $dataType = $this->createDataTypeTestDouble('posts');
        $model = $this->createModelTestDouble(12);

        $action->setup($dataType, $model);

        $this->assertSame($dataType, $this->getNonPublicProperty($action, 'dataType'));
        $this->assertSame($model, $this->getNonPublicProperty($action, 'model'));
    }

    /**
     * @covers ::tag
     * @covers ::getTag
     */
    public function test_tag_sets_tag(): void
    {
        $action = new BaseActionTestElement;

        $this->assertSame('a', $action->getTag());
        $this->assertSame($action, $action->tag('button'));
        $this->assertSame('button', $action->getTag());
    }

    /**
     * @covers ::icon
     * @covers ::getIcon
     */
    public function test_icon_sets_icon(): void
    {
        $action = new BaseActionTestElement;

        $this->assertSame($action, $action->icon('pencil'));
        $this->assertSame('pencil', $action->getIcon());
    }

    /**
     * @covers ::color
     * @covers ::getColor
     */
    public function test_color_sets_color(): void
    {
        $action = new BaseActionTestElement;

        $this->assertSame($action, $action->color('primary'));
        $this->assertSame('primary', $action->getColor());
    }

    /**
     * @covers ::title
     * @covers ::getTitle
     */
    public function test_title_sets_title(): void
    {
        $action = new BaseActionTestElement;

        $this->assertSame($action, $action->title('Edit'));
        $this->assertSame('Edit', $action->getTitle());
    }

    /**
     * @covers ::route
     * @covers ::getRoute
     */
    public function test_route_sets_route(): void
    {
        $action = new BaseActionTestElement;

        $this->assertSame($action, $action->route('/admin/posts/1/edit'));
        $this->assertSame('/admin/posts/1/edit', $action->getRoute());
    }

    /**
     * @covers ::policyName
     * @covers ::getPolicyName
     */
    public function test_policy_name_sets_policy_name(): void
    {
        $action = new BaseActionTestElement;

        $this->assertSame($action, $action->policyName('update'));
        $this->assertSame('update', $action->getPolicyName());
    }

    /**
     * @covers ::template
     * @covers ::getTemplate
     */
    public function test_template_returns_default_or_custom_template(): void
    {
        $action = new BaseActionTestElement;

        $this->assertSame('adminpanel::datatables.actions.button', $action->getTemplate());
        $this->assertSame($action, $action->template('custom.action'));
        $this->assertSame('custom.action', $action->getTemplate());
    }

    /**
     * @covers ::attributes
     * @covers ::getAttributes
     */
    public function test_attributes_sets_attributes(): void
    {
        $action = new BaseActionTestElement;

        $this->assertSame($action, $action->attributes(['class' => 'btn']));
        $this->assertSame(['class' => 'btn'], $action->getAttributes());
    }

    /**
     * @covers ::convertAttributesToHtml
     */
    public function test_convert_attributes_to_html_concatenates_attributes(): void
    {
        $action = (new BaseActionTestElement)->attributes([
            'class' => 'btn',
            'data-id' => 7,
        ]);

        $this->assertSame('class="btn"data-id="7"', $action->convertAttributesToHtml());
    }
}

class BaseActionTestElement extends BaseAction {}

<?php

namespace KY\AdminPanel\Tests\Unit\DataTables\Actions;

use KY\AdminPanel\DataTables\Actions\DeleteAction;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\DataTables\Actions\DeleteAction
 */
class DeleteActionTest extends TestCase
{
    /**
     * @covers ::getAttributes
     */
    public function test_get_attributes_returns_default_delete_attributes(): void
    {
        $action = new DeleteAction();
        $action->setup($this->createDataTypeTestDouble('posts'), $this->createModelTestDouble(7));

        $this->assertSame([
            'class' => 'btn btn-danger btn-sm ml-1',
            'data-action' => 'deleteModel',
            'data-slug' => 'posts',
            'data-id' => 7,
        ], $action->getAttributes());
    }

    /**
     * @covers ::getAttributes
     */
    public function test_get_attributes_prefers_custom_attributes(): void
    {
        $action = new DeleteAction();

        $action->attributes(['class' => 'custom']);

        $this->assertSame(['class' => 'custom'], $action->getAttributes());
    }
}

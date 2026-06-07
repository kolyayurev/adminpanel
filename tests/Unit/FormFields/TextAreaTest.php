<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\FormFields\TextArea;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\TextArea
 */
class TextAreaTest extends TestCase
{
    /**
     * @covers ::rows
     */
    public function test_rows_sets_rows_attribute(): void
    {
        $field = new TextArea;

        $this->assertSame($field, $field->rows(8));
        $this->assertSame(8, $field->get('rows'));
    }
}

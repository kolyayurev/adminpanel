<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\FormFields\Date;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Date
 */
class DateTest extends TestCase
{
    /**
     * @covers ::hasFormat
     */
    public function test_has_format_returns_true_when_format_is_not_empty(): void
    {
        $this->assertTrue((new Date())->hasFormat());
    }

    /**
     * @covers ::format
     * @covers ::hasFormat
     */
    public function test_format_sets_format_attribute(): void
    {
        $field = new Date();

        $this->assertSame($field, $field->format('Y-m-d H:i:s'));
        $this->assertSame('Y-m-d H:i:s', $field->get('format'));
        $this->assertTrue($field->hasFormat());
    }
}

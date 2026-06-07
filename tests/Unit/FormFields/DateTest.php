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

    /**
     * @covers ::getFormattedValue
     */
    public function test_get_formatted_value_supports_default_strftime_format(): void
    {
        $field = (new Date())->name('published_at');
        $model = (object) ['published_at' => '2026-06-07 15:30:00'];

        $this->assertSame('2026-06-07', $field->getFormattedValue($model));
    }

    /**
     * @covers ::getFormattedValue
     */
    public function test_get_formatted_value_supports_php_date_format(): void
    {
        $field = (new Date())->name('published_at')->format('Y-m-d H:i:s');
        $model = (object) ['published_at' => '2026-06-07 15:30:00'];

        $this->assertSame('2026-06-07 15:30:00', $field->getFormattedValue($model));
    }
}

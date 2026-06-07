<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use Illuminate\Http\Request;
use KY\AdminPanel\FormFields\Status;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Status
 */
class StatusTest extends TestCase
{
    /**
     * @covers ::prepareValue
     */
    public function test_prepare_value_converts_truthy_one_to_integer(): void
    {
        $field = new Status;

        $this->assertSame(1, $field->prepareValue('1', new Request, null));
        $this->assertSame(0, $field->prepareValue('on', new Request, null));
    }
}

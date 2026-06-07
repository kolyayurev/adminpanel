<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use Illuminate\Http\Request;
use KY\AdminPanel\FormFields\Coordinates;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Coordinates
 */
class CoordinatesTest extends TestCase
{
    /**
     * @covers ::placeholder
     * @covers ::getPlaceholder
     */
    public function test_placeholder_sets_placeholder_attribute(): void
    {
        $field = new Coordinates();

        $this->assertSame($field, $field->placeholder('55.1, 37.1'));
        $this->assertSame('55.1, 37.1', $field->getPlaceholder());
    }

    /**
     * @covers ::holdAsObject
     * @covers ::prepareValue
     */
    public function test_prepare_value_returns_decoded_object_when_holding_as_object(): void
    {
        $field = (new Coordinates())->holdAsObject();

        $this->assertSame(
            ['coords' => [55.1, 37.1]],
            $field->prepareValue('{"coords":[55.1,37.1]}', new Request(), null)
        );
    }

    /**
     * @covers ::holdAsPoint
     * @covers ::prepareValue
     */
    public function test_prepare_value_returns_coords_when_holding_as_point(): void
    {
        $field = (new Coordinates())->holdAsPoint();

        $this->assertSame(
            [55.1, 37.1],
            $field->prepareValue('{"coords":[55.1,37.1]}', new Request(), null)
        );
    }
}

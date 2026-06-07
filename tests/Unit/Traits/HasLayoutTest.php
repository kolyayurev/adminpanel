<?php

namespace KY\AdminPanel\Tests\Unit\Traits;

use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\Blocks\Col;
use KY\AdminPanel\Blocks\Row;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Traits\HasLayout;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\HasLayout
 */
class HasLayoutTest extends TestCase
{
    /**
     * @covers ::layout
     */
    public function test_layout_returns_default_row_col_card_layout(): void
    {
        $layout = (new HasLayoutTestElement())->layout();

        $row = $layout->first();
        $col = $row->getBlocks()->first();
        $card = $col->getBlocks()->first();

        $this->assertInstanceOf(Row::class, $row);
        $this->assertInstanceOf(Col::class, $col);
        $this->assertInstanceOf(Card::class, $card);
        $this->assertSame(['*'], $card->getBlocks()->all());
    }

    /**
     * @covers ::getLayout
     */
    public function test_get_layout_returns_layout(): void
    {
        $element = new HasLayoutTestElement();

        $this->assertEquals($element->layout(), $element->getLayout());
    }
}

class HasLayoutTestElement
{
    use HasLayout;
}

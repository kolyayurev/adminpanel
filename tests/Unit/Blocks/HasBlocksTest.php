<?php

namespace KY\AdminPanel\Tests\Unit\Blocks;

use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\Blocks\Col;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Traits\HasBlocks;

/**
 * @coversDefaultClass \KY\AdminPanel\Traits\HasBlocks
 */
class HasBlocksTest extends TestCase
{
    /**
     * @covers ::blocks
     */
    public function test_blocks_creates_instance_with_blocks(): void
    {
        $block = HasBlocksTestElement::blocks('title', 'body');

        $this->assertInstanceOf(HasBlocksTestElement::class, $block);
        $this->assertSame(['title', 'body'], $block->getBlocks()->all());
    }

    /**
     * @covers ::addBlock
     */
    public function test_add_block_appends_block(): void
    {
        $block = new HasBlocksTestElement();

        $result = $block->addBlock('title');

        $this->assertSame($block, $result);
        $this->assertSame(['title'], $block->getBlocks()->all());
    }

    /**
     * @covers ::getBlocks
     */
    public function test_get_blocks_returns_collection(): void
    {
        $block = HasBlocksTestElement::blocks('title');

        $this->assertSame(['title'], $block->getBlocks()->all());
    }

    /**
     * @covers ::getFieldsName
     */
    public function test_get_fields_name_flattens_nested_block_field_names(): void
    {
        $block = HasBlocksTestElement::blocks(
            Card::blocks('title', 'lead'),
            Col::blocks('body')
        );

        $this->assertSame(['title', 'lead', 'body'], $block->getFieldsName()->values()->all());
    }
}

class HasBlocksTestElement
{
    use HasBlocks;
}

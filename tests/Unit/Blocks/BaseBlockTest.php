<?php

namespace KY\AdminPanel\Tests\Unit\Blocks;

use KY\AdminPanel\Blocks\BaseBlock;
use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\Blocks\Col;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Blocks\BaseBlock
 */
class BaseBlockTest extends TestCase
{
    /**
     * @covers ::getType
     */
    public function test_get_type_returns_snake_case_class_basename(): void
    {
        $this->assertSame('base_block_test_element', (new BaseBlockTestElement)->getType());
    }

    /**
     * @covers ::class
     * @covers ::getClass
     */
    public function test_class_sets_css_class(): void
    {
        $block = new BaseBlockTestElement;

        $result = $block->class('panel panel-default');

        $this->assertSame($block, $result);
        $this->assertSame('panel panel-default', $block->getClass());
    }

    /**
     * @covers ::getBeforeTemplate
     * @covers ::beforeTemplate
     */
    public function test_before_template_returns_default_or_custom_template(): void
    {
        $block = new BaseBlockTestElement;

        $this->assertSame('adminpanel::blocks.layout.before', $block->getBeforeTemplate());
        $this->assertSame($block, $block->beforeTemplate('custom.before'));
        $this->assertSame('custom.before', $block->getBeforeTemplate());
    }

    /**
     * @covers ::getTemplate
     * @covers ::template
     */
    public function test_template_returns_default_or_custom_template(): void
    {
        $block = new BaseBlockTestElement;

        $this->assertSame('adminpanel::blocks.base_block_test_element.index', $block->getTemplate());
        $this->assertSame($block, $block->template('custom.index'));
        $this->assertSame('custom.index', $block->getTemplate());
    }

    /**
     * @covers ::getAfterTemplate
     * @covers ::afterTemplate
     */
    public function test_after_template_returns_default_or_custom_template(): void
    {
        $block = new BaseBlockTestElement;

        $this->assertSame('adminpanel::blocks.layout.after', $block->getAfterTemplate());
        $this->assertSame($block, $block->afterTemplate('custom.after'));
        $this->assertSame('custom.after', $block->getAfterTemplate());
    }

    /**
     * @covers ::instruction
     * @covers ::getInstruction
     */
    public function test_instruction_sets_instruction_payload(): void
    {
        $block = new BaseBlockTestElement;

        $result = $block->instruction(['text' => 'Read this']);

        $this->assertSame($block, $result);
        $this->assertSame(['text' => 'Read this'], $block->getInstruction());
    }

    /**
     * @covers ::visibleOnlyWhenHasFields
     * @covers ::isVisibleOnlyWhenHasFields
     */
    public function test_visible_only_when_has_fields_sets_flag(): void
    {
        $block = new BaseBlockTestElement;

        $this->assertFalse($block->isVisibleOnlyWhenHasFields());
        $this->assertSame($block, $block->visibleOnlyWhenHasFields());
        $this->assertTrue($block->isVisibleOnlyWhenHasFields());
    }

    /**
     * @covers ::toArray
     */
    public function test_to_array_returns_block_state_and_nested_blocks(): void
    {
        $block = BaseBlockTestElement::blocks(Card::blocks('title'), Col::blocks('body'));
        $block->class('wrapper');

        $array = $block->toArray();

        $this->assertSame('base_block_test_element', $array['type']);
        $this->assertSame('wrapper', $array['class']);
        $this->assertCount(2, $array['blocks']);
    }
}

class BaseBlockTestElement extends BaseBlock {}

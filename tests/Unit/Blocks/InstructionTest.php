<?php

namespace KY\AdminPanel\Tests\Unit\Blocks;

use KY\AdminPanel\Blocks\Instruction;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Blocks\Instruction
 */
class InstructionTest extends TestCase
{
    /**
     * @covers ::text
     * @covers ::getText
     */
    public function test_text_sets_text(): void
    {
        $instruction = new Instruction;

        $this->assertSame($instruction, $instruction->text('Read this'));
        $this->assertSame('Read this', $instruction->getText());
    }
}

<?php

namespace KY\AdminPanel\Tests\Unit\Blocks;

use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Blocks\Card
 */
class CardTest extends TestCase
{
    /**
     * @covers ::header
     * @covers ::getHeader
     */
    public function test_header_sets_header(): void
    {
        $card = new Card();

        $result = $card->header('Main');

        $this->assertSame($card, $result);
        $this->assertSame('Main', $card->getHeader());
    }

    /**
     * @covers ::hasHeader
     */
    public function test_has_header_returns_true_only_when_header_exists(): void
    {
        $card = new Card();

        $this->assertFalse($card->hasHeader());
        $card->header('Main');
        $this->assertTrue($card->hasHeader());
    }
}

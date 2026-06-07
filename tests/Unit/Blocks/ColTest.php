<?php

namespace KY\AdminPanel\Tests\Unit\Blocks;

use KY\AdminPanel\Blocks\Col;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Blocks\Col
 */
class ColTest extends TestCase
{
    /**
     * @covers ::xs
     * @covers ::getXs
     */
    public function test_xs_sets_validated_xs_columns(): void
    {
        $col = new Col();

        $this->assertSame($col, $col->xs(6));
        $this->assertSame(6, $col->getXs());
    }

    /**
     * @covers ::sm
     * @covers ::getSm
     */
    public function test_sm_sets_validated_sm_columns(): void
    {
        $col = new Col();

        $this->assertSame($col, $col->sm(5));
        $this->assertSame(5, $col->getSm());
    }

    /**
     * @covers ::md
     * @covers ::getMd
     */
    public function test_md_sets_validated_md_columns(): void
    {
        $col = new Col();

        $this->assertSame($col, $col->md(4));
        $this->assertSame(4, $col->getMd());
    }

    /**
     * @covers ::lg
     * @covers ::getLg
     */
    public function test_lg_sets_validated_lg_columns(): void
    {
        $col = new Col();

        $this->assertSame($col, $col->lg(3));
        $this->assertSame(3, $col->getLg());
    }

    /**
     * @covers ::getColumns
     */
    public function test_get_columns_returns_bootstrap_column_classes(): void
    {
        $col = (new Col())->xs(6)->sm(5)->md(4)->lg(3);

        $this->assertSame('col-6 col-sm-5 col-md-4 col-lg-3', $col->getColumns());
    }

    /**
     * @covers ::validate
     */
    public function test_validate_returns_twelve_for_out_of_range_values(): void
    {
        $col = new Col();

        $this->assertSame(12, $this->callNonPublicMethod($col, 'validate', [0]));
        $this->assertSame(12, $this->callNonPublicMethod($col, 'validate', [13]));
        $this->assertSame(7, $this->callNonPublicMethod($col, 'validate', [7]));
    }
}

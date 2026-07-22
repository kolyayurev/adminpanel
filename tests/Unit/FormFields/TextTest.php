<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use Illuminate\Support\Str;
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Text
 */
class TextTest extends TestCase
{
    /**
     * @covers ::default
     */
    public function test_default_sets_default_attribute(): void
    {
        $field = new Text;

        $this->assertSame($field, $field->default('Untitled'));
        $this->assertSame('Untitled', $field->get('default'));
    }

    /**
     * @covers ::type
     */
    public function test_type_sets_type_attribute(): void
    {
        $field = new Text;

        $this->assertSame($field, $field->type('email'));
        $this->assertSame('email', $field->get('type'));
    }

    /**
     * @covers ::required
     */
    public function test_required_sets_required_attribute(): void
    {
        $field = new Text;

        $this->assertSame($field, $field->required());
        $this->assertTrue($field->get('required'));
    }

    /**
     * @covers ::getValue
     */
    public function test_cell_rendering_breaks_on_json_cast_column(): void
    {
        // Колонка на json/jsonb-каст (массив в PHP) роняет ячейку списка — шаблон
        // cell.blade.php делает Str::limit($field->getValue($model), 50), а Str::limit
        // ожидает строку. Решение — отдельное поле Json, а не правка Text.
        $field = Text::make('meta');
        $model = (object) ['meta' => ['nested' => true]];

        $this->expectException(\TypeError::class);

        Str::limit($field->getValue($model), 50);
    }
}

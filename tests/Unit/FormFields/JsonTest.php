<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\FormFields\Json;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Json
 */
class JsonTest extends TestCase
{
    /**
     * @covers ::getSlug
     */
    public function test_get_slug_uses_json(): void
    {
        $this->assertSame('json', (new Json)->getSlug());
    }

    /**
     * @covers ::__construct
     */
    public function test_construct_hides_field_from_list_and_forms_by_default(): void
    {
        // По умолчанию поле только для просмотра (страница записи) — как в проекте-потребителе:
        // редактирование json "в лоб" текстом не даём.
        $field = new Json;

        $this->assertSame(['list', 'create', 'edit'], $field->get('hiddenOn'));
    }

    /**
     * @covers ::getValue
     */
    public function test_get_value_pretty_prints_array_value(): void
    {
        $field = Json::make('meta');
        $model = (object) ['meta' => ['a' => 1, 'b' => 'текст']];

        $this->assertSame(
            json_encode(['a' => 1, 'b' => 'текст'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            $field->getValue($model)
        );
    }

    /**
     * @covers ::getValue
     */
    public function test_get_value_returns_empty_string_for_null(): void
    {
        $field = Json::make('meta');
        $model = (object) ['meta' => null];

        $this->assertSame('', $field->getValue($model));
    }
}

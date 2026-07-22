<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use Illuminate\Http\Request;
use KY\AdminPanel\FormFields\EnumSelect;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\EnumSelect
 */
class EnumSelectTest extends TestCase
{
    /**
     * @covers ::getSlug
     */
    public function test_get_slug_reuses_select_views(): void
    {
        // Своих вьюх у EnumSelect нет — переиспользует cell/form/show обычного Select,
        // т.к. после getValue() у него на руках уже голый скаляр, а не объект enum.
        $this->assertSame('select', (new EnumSelect)->getSlug());
    }

    /**
     * @covers ::enum
     * @covers ::getOptions
     */
    public function test_enum_builds_options_from_cases(): void
    {
        $field = (new EnumSelect)->enum(EnumSelectTestStatus::class);

        $this->assertSame([
            'draft' => 'Draft',
            'published' => 'Published',
        ], $field->getOptions());
    }

    /**
     * @covers ::enum
     * @covers ::getOptions
     */
    public function test_enum_uses_label_method_when_enum_provides_it(): void
    {
        $field = (new EnumSelect)->enum(EnumSelectTestStatusWithLabel::class);

        $this->assertSame([
            'draft' => 'Черновик',
            'published' => 'Опубликовано',
        ], $field->getOptions());
    }

    /**
     * @covers ::enum
     */
    public function test_enum_does_not_override_explicitly_set_options(): void
    {
        $field = (new EnumSelect)->options(['draft' => 'Черновик'])->enum(EnumSelectTestStatus::class);

        $this->assertSame(['draft' => 'Черновик'], $field->getOptions());
    }

    /**
     * @covers ::getValue
     */
    public function test_get_value_unwraps_backed_enum_to_scalar(): void
    {
        $field = EnumSelect::make('status');
        $model = (object) ['status' => EnumSelectTestStatus::Published];

        $this->assertSame('published', $field->getValue($model));
    }

    /**
     * @covers ::getValue
     */
    public function test_get_value_returns_raw_scalar_when_column_is_not_cast_to_enum(): void
    {
        $field = EnumSelect::make('status');
        $model = (object) ['status' => 'draft'];

        $this->assertSame('draft', $field->getValue($model));
    }

    /**
     * @covers ::getOption
     */
    public function test_get_option_resolves_label_for_value_read_from_enum(): void
    {
        $field = EnumSelect::make('status')->enum(EnumSelectTestStatus::class);
        $model = (object) ['status' => EnumSelectTestStatus::Published];

        $this->assertSame('Published', $field->getOption($field->getValue($model)));
    }

    /**
     * @covers ::prepareValue
     */
    public function test_prepare_value_keeps_scalar_as_is_for_eloquent_cast_to_convert(): void
    {
        // Eloquent сам преобразует скаляр обратно в BackedEnum через каст на модели —
        // дополнительной конвертации в поле быть не должно.
        $field = new EnumSelect;

        $this->assertSame('published', $field->prepareValue('published', new Request, null));
    }
}

enum EnumSelectTestStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
}

enum EnumSelectTestStatusWithLabel: string
{
    case Draft = 'draft';
    case Published = 'published';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Черновик',
            self::Published => 'Опубликовано',
        };
    }
}

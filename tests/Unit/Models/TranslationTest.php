<?php

namespace KY\AdminPanel\Tests\Unit\Models;

use KY\AdminPanel\Models\Translation;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Models\Translation
 */
class TranslationTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function test_model_persists_fillable_attributes(): void
    {
        $translation = Translation::factory()->create([
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => 10,
            'locale' => 'en',
            'value' => 'English value',
        ]);

        $this->assertInstanceOf(Translation::class, $translation);
        $this->assertSame('settings', $translation->table_name);
        $this->assertSame('value', $translation->column_name);
        $this->assertSame(10, $translation->foreign_key);
        $this->assertSame('en', $translation->locale);
        $this->assertSame('English value', $translation->value);
    }
}

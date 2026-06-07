<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\FormFields\TextEditor;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\TextEditor
 */
class TextEditorTest extends TestCase
{
    /**
     * @covers ::height
     */
    public function test_height_sets_height_attribute(): void
    {
        $field = new TextEditor;

        $this->assertSame($field, $field->height(420));
        $this->assertSame(420, $field->get('height'));
    }

    /**
     * @covers ::contentCss
     */
    public function test_content_css_camel_case_sets_content_css_attribute(): void
    {
        $field = new TextEditor;

        $this->assertSame($field, $field->contentCss('/editor.css'));
        $this->assertSame('/editor.css', $field->get('content_css'));
    }

    /**
     * @covers ::content_css
     */
    public function test_content_css_snake_case_sets_content_css_attribute(): void
    {
        $field = new TextEditor;

        $this->assertSame($field, $field->content_css('/editor.css'));
        $this->assertSame('/editor.css', $field->get('content_css'));
    }

    /**
     * @covers ::toolbar2
     */
    public function test_toolbar2_sets_toolbar2_attribute(): void
    {
        $field = new TextEditor;

        $this->assertSame($field, $field->toolbar2('bold italic'));
        $this->assertSame('bold italic', $field->get('toolbar2'));
    }

    /**
     * @covers ::getOptions
     */
    public function test_get_options_returns_editor_options_without_field_metadata(): void
    {
        $options = TextEditor::make('body')
            ->label('Body')
            ->height(420)
            ->contentCss('/editor.css')
            ->getOptions();

        $this->assertSame(420, $options['height']);
        $this->assertSame('/editor.css', $options['content_css']);
        $this->assertArrayNotHasKey('class', $options);
        $this->assertArrayNotHasKey('value', $options);
        $this->assertArrayNotHasKey('name', $options);
        $this->assertArrayNotHasKey('label', $options);
    }
}

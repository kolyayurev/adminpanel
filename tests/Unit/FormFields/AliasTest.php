<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use KY\AdminPanel\FormFields\Alias;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Alias
 */
class AliasTest extends TestCase
{
    /**
     * @covers ::changeOnTyping
     */
    public function test_change_on_typing_sets_change_on_typing_attribute(): void
    {
        $field = new Alias;

        $this->assertSame($field, $field->changeOnTyping(true));
        $this->assertTrue($field->get('changeOnTyping'));
    }

    /**
     * @covers ::forceUpdate
     */
    public function test_force_update_sets_force_update_attribute(): void
    {
        $field = new Alias;

        $this->assertSame($field, $field->forceUpdate(true));
        $this->assertTrue($field->get('forceUpdate'));
    }

    /**
     * @covers ::source
     */
    public function test_source_sets_source_attribute(): void
    {
        $field = new Alias;

        $this->assertSame($field, $field->source('title,subtitle'));
        $this->assertSame('title,subtitle', $field->get('source'));
    }

    /**
     * @covers ::route
     * @covers ::getRoute
     * @covers ::hasRoute
     */
    public function test_route_sets_route_attribute(): void
    {
        $field = new Alias;

        $this->assertFalse($field->hasRoute());
        $this->assertSame($field, $field->route('posts.show'));
        $this->assertSame('posts.show', $field->getRoute());
        $this->assertTrue($field->hasRoute());
    }

    /**
     * @covers ::buildRoute
     */
    public function test_build_route_builds_named_route_from_model_field_value(): void
    {
        Route::get('/posts/{slug}', fn () => '')->name('posts.show');

        $field = Alias::make('slug')->route('posts.show');
        $model = new class extends Model
        {
            protected $guarded = [];
        };
        $model->slug = 'hello-world';

        $this->assertSame('http://localhost/posts/hello-world', $field->buildRoute($model));
    }
}

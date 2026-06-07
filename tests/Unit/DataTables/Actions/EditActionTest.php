<?php

namespace KY\AdminPanel\Tests\Unit\DataTables\Actions;

use Illuminate\Support\Facades\Route;
use KY\AdminPanel\DataTables\Actions\EditAction;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\DataTables\Actions\EditAction
 */
class EditActionTest extends TestCase
{
    /**
     * @covers ::getRoute
     */
    public function test_get_route_returns_adminpanel_edit_route(): void
    {
        Route::get('/admin/posts/{post}/edit', static fn () => '')->name('adminpanel.posts.edit');

        $action = new EditAction();
        $action->setup($this->createDataTypeTestDouble('posts'), $this->createModelTestDouble(7));

        $this->assertSame('http://localhost/admin/posts/7/edit', $action->getRoute());
    }
}

<?php

namespace KY\AdminPanel\Tests\Unit\DataTables\Actions;

use Illuminate\Support\Facades\Route;
use KY\AdminPanel\DataTables\Actions\ShowAction;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\DataTables\Actions\ShowAction
 */
class ShowActionTest extends TestCase
{
    /**
     * @covers ::getRoute
     */
    public function test_get_route_returns_adminpanel_show_route(): void
    {
        Route::get('/admin/posts/{post}', static fn () => '')->name('adminpanel.posts.show');

        $action = new ShowAction();
        $action->setup($this->createDataTypeTestDouble('posts'), $this->createModelTestDouble(7));

        $this->assertSame('http://localhost/admin/posts/7', $action->getRoute());
    }
}

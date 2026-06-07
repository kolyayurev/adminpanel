<?php

namespace KY\AdminPanel\Tests\Unit\Models;

use KY\AdminPanel\Models\Redirect;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Models\Redirect
 */
class RedirectTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function test_model_persists_fillable_attributes_and_casts_get_params(): void
    {
        $redirect = Redirect::factory()->create([
            'from' => '/old',
            'get_params' => ['utm' => 'email'],
            'to' => '/new',
            'status' => 301,
        ]);

        $this->assertInstanceOf(Redirect::class, $redirect);
        $this->assertSame('/old', $redirect->from);
        $this->assertSame(['utm' => 'email'], $redirect->get_params);
        $this->assertSame('/new', $redirect->to);
        $this->assertSame(301, $redirect->status);
        $this->assertFalse($redirect->usesTimestamps());
    }
}

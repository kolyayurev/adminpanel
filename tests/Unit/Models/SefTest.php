<?php

namespace KY\AdminPanel\Tests\Unit\Models;

use KY\AdminPanel\Models\Sef;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Models\Sef
 */
class SefTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function test_model_persists_fillable_attributes_and_casts_get_params(): void
    {
        $sef = Sef::factory()->create([
            'url' => '/source',
            'get_params' => ['page' => '2'],
            'alias' => '/alias',
            'status' => 1,
        ]);

        $this->assertInstanceOf(Sef::class, $sef);
        $this->assertSame('/source', $sef->url);
        $this->assertSame(['page' => '2'], $sef->get_params);
        $this->assertSame('/alias', $sef->alias);
        $this->assertSame(1, $sef->status);
        $this->assertFalse($sef->usesTimestamps());
    }
}

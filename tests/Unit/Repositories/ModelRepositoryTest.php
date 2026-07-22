<?php

namespace KY\AdminPanel\Tests\Unit\Repositories;

use KY\AdminPanel\Models\Redirect;
use KY\AdminPanel\Repositories\ModelRepository;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Repositories\ModelRepository
 */
class ModelRepositoryTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::modelClass
     * @covers ::model
     */
    public function test_model_class_and_model_resolve_configured_class(): void
    {
        $repository = new ModelRepository(Redirect::class);

        $this->assertSame(Redirect::class, $repository->modelClass());
        $this->assertInstanceOf(Redirect::class, $repository->model());
    }
}

<?php

namespace KY\AdminPanel\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Foundation\Testing\RefreshDatabase;
use KY\AdminPanel\AdminPanelServiceProvider;
use KY\AdminPanel\Tests\Utils\Traits\CreatesAdminPanelModels;
use KY\AdminPanel\Tests\Utils\Traits\CreatesDataTableTestDoubles;
use KY\AdminPanel\Tests\Utils\Traits\ReflationTestTrait;

class TestCase extends Orchestra
{
    use CreatesAdminPanelModels;
    use CreatesDataTableTestDoubles;
    use RefreshDatabase;
    use ReflationTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'KY\\AdminPanel\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            AdminPanelServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../vendor/orchestra/testbench-core/laravel/migrations');
    }
}

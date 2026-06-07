<?php

namespace KY\AdminPanel\Tests\Unit\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use KY\AdminPanel\Models\BaseModel;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Models\BaseModel
 */
class BaseModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('visible_test_models', function (Blueprint $table): void {
            $table->id();
            $table->boolean('visible')->default(false);
        });
    }

    /**
     * @covers ::scopeVisible
     */
    public function test_scope_visible_filters_only_visible_records(): void
    {
        $visible = VisibleTestModel::query()->create(['visible' => true]);
        VisibleTestModel::query()->create(['visible' => false]);

        $models = VisibleTestModel::query()->visible()->get();

        $this->assertCount(1, $models);
        $this->assertTrue($visible->is($models->first()));
    }
}

class VisibleTestModel extends BaseModel
{
    protected $table = 'visible_test_models';

    protected $guarded = [];

    public $timestamps = false;
}

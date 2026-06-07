<?php

namespace KY\AdminPanel\Tests\Unit\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use KY\AdminPanel\DataTypes\BaseDataType;
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Models\Redirect;
use KY\AdminPanel\Models\User;
use KY\AdminPanel\Repositories\BaseRepository;
use KY\AdminPanel\Repositories\RedirectRepository;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Repositories\BaseRepository
 */
class BaseRepositoryTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::model
     * @covers ::modelClass
     */
    public function test_construct_sets_model_from_model_class(): void
    {
        $repository = new BaseRepositoryTestRepository();

        $this->assertInstanceOf(Model::class, $this->getNonPublicProperty($repository, 'model'));
    }

    /**
     * @covers ::create
     */
    public function test_create_persists_model_from_data(): void
    {
        $repository = new RedirectRepository();

        $redirect = $repository->create([
            'from' => '/old',
            'to' => '/new',
            'status' => true,
        ]);

        $this->assertInstanceOf(Redirect::class, $redirect);
        $this->assertDatabaseHas('redirects', [
            'from' => '/old',
            'to' => '/new',
            'status' => true,
        ]);
    }

    /**
     * @covers ::getDataTableFilter
     */
    public function test_get_data_table_filter_applies_column_filter_and_stores_session_value(): void
    {
        Redirect::factory()->create(['from' => '/old-page']);
        Redirect::factory()->create(['from' => '/other-page']);

        $repository = new RedirectRepository();
        $dataType = new BaseRepositoryTestDataType($repository);

        $query = $repository->getDataTableFilter(new Request(['from' => 'old']), $dataType);

        $this->assertSame('/old-page', $query->first()->from);
        $this->assertSame('old', session('datatable.redirects.from'));
    }
}

class BaseRepositoryTestRepository extends BaseRepository
{
    public function modelClass(): string
    {
        return User::class;
    }
}

class BaseRepositoryTestDataType extends BaseDataType
{
    protected string $slug = 'redirects';

    public function __construct(RedirectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fields(): \Illuminate\Support\Collection
    {
        return collect([
            Text::make('from')->label('From'),
        ]);
    }
}

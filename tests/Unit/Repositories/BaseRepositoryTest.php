<?php

namespace KY\AdminPanel\Tests\Unit\Repositories;

use AdminPanel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use KY\AdminPanel\Contracts\RepositoryContract;
use KY\AdminPanel\DataTypes\BaseDataType;
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Models\Redirect;
use KY\AdminPanel\Models\User;
use KY\AdminPanel\Repositories\BaseRepository;
use KY\AdminPanel\Repositories\RedirectRepository;
use KY\AdminPanel\Repositories\UserRepository;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Tests\Utils\Fixtures\CustomTableUser;

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
    public function test_construct_does_not_eagerly_resolve_model(): void
    {
        $repository = new BaseRepositoryTestRepository;

        // Модель больше не резолвится и не кэшируется в конструкторе (T23) — иначе
        // AdminPanel::useModel(), вызванный ПОСЛЕ конструирования репозитория, не успевает
        // подействовать на уже закэшированное свойство.
        $this->assertNull($this->getNonPublicProperty($repository, 'model'));
        $this->assertInstanceOf(Model::class, $repository->model());
    }

    /**
     * @covers ::create
     */
    public function test_create_persists_model_from_data(): void
    {
        $repository = new RedirectRepository;

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

        $repository = new RedirectRepository;
        $dataType = new BaseRepositoryTestDataType($repository);

        $query = $repository->getDataTableFilter(new Request(['from' => 'old']), $dataType);

        $this->assertSame('/old-page', $query->first()->from);
        $this->assertSame('old', session('datatable.redirects.from'));
    }

    /**
     * @covers ::getDataTableFilter
     */
    public function test_get_data_table_filter_follows_model_swapped_after_construction(): void
    {
        // Таблица под подменённую модель — как admin_users в приложении-потребителе.
        Schema::create('admin_users', fn (Blueprint $table) => $table->id());

        // Репозиторий конструируется ДО useModel() — так же, как пакет строит UserDataType
        // в своём register(), раньше, чем приложение-потребитель успевает подменить модель.
        $repository = new UserRepository;

        AdminPanel::useModel('User', CustomTableUser::class);

        $dataType = new BaseRepositoryTestDataType($repository);
        $query = $repository->getDataTableFilter(new Request, $dataType);

        $this->assertSame('admin_users', $query->getModel()->getTable());
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

    public function __construct(RepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function fields(): Collection
    {
        return collect([
            Text::make('from')->label('From'),
        ]);
    }
}

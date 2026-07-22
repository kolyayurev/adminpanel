<?php

namespace KY\AdminPanel\Tests\Unit\DataTypes;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\Blocks\Row;
use KY\AdminPanel\DataTables\Actions\DeleteAction;
use KY\AdminPanel\DataTables\Actions\EditAction;
use KY\AdminPanel\DataTables\Actions\ShowAction;
use KY\AdminPanel\DataTypes\BaseDataType;
use KY\AdminPanel\FormFields\Hidden;
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Http\Controllers\BaseDataController;
use KY\AdminPanel\Models\Redirect;
use KY\AdminPanel\Policies\BasePolicy;
use KY\AdminPanel\Repositories\ModelRepository;
use KY\AdminPanel\Repositories\UserRepository;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\DataTypes\BaseDataType
 */
class BaseDataTypeTest extends TestCase
{
    /**
     * @covers ::getName
     * @covers ::getIcon
     * @covers ::getTitle
     * @covers ::getSingleTitle
     * @covers ::getPluralTitle
     */
    public function test_basic_getters_return_configured_values(): void
    {
        $dataType = new BaseDataTypeTestElement;

        $this->assertSame('user', $dataType->getName());
        $this->assertSame('bi-user', $dataType->getIcon());
        $this->assertSame('Users', $dataType->getTitle());
        $this->assertSame('User', $dataType->getSingleTitle());
        $this->assertSame('Users', $dataType->getPluralTitle());
    }

    /**
     * @covers ::getSlug
     */
    public function test_get_slug_returns_configured_or_generated_slug(): void
    {
        $this->assertSame('custom_users', (new BaseDataTypeTestElement)->getSlug());
        $this->assertSame('base_data_type_generated', (new BaseDataTypeGenerated)->getSlug());
    }

    /**
     * @covers ::getModel
     */
    public function test_get_model_returns_repository_model(): void
    {
        $this->assertSame('users', (new BaseDataTypeTestElement)->getModel()->getTable());
    }

    /**
     * @covers ::getOrderColumn
     * @covers ::getOrderDisplayColumn
     * @covers ::getOrderDirection
     * @covers ::showOrderPage
     */
    public function test_order_getters_return_order_configuration(): void
    {
        $dataType = new BaseDataTypeTestElement;

        $this->assertSame('position', $dataType->getOrderColumn());
        $this->assertSame('name', $dataType->getOrderDisplayColumn());
        $this->assertSame('asc', $dataType->getOrderDirection());
        $this->assertSame('1', $dataType->showOrderPage());
    }

    /**
     * @covers ::getPolicy
     * @covers ::getController
     */
    public function test_policy_and_controller_getters_return_defaults(): void
    {
        $dataType = new BaseDataTypeTestElement;

        $this->assertSame(BasePolicy::class, $dataType->getPolicy());
        $this->assertSame(BaseDataController::class, $dataType->getController());
    }

    /**
     * @covers ::getIndexView
     * @covers ::getFormView
     * @covers ::getShowView
     * @covers ::getOrderView
     */
    public function test_view_getters_return_common_views(): void
    {
        $dataType = new BaseDataTypeTestElement;

        $this->assertSame('adminpanel::datatypes.common.index', $dataType->getIndexView());
        $this->assertSame('adminpanel::datatypes.common.form', $dataType->getFormView());
        $this->assertSame('adminpanel::datatypes.common.show', $dataType->getShowView());
        $this->assertSame('adminpanel::datatypes.common.order', $dataType->getOrderView());
    }

    /**
     * @covers ::rules
     * @covers ::messages
     * @covers ::validator
     */
    public function test_validator_uses_rules_messages_and_custom_attributes(): void
    {
        $validator = (new BaseDataTypeTestElement)->validator(new Request(['name' => 'ab']));

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /**
     * @covers ::customAttributes
     */
    public function test_custom_attributes_returns_field_labels_keyed_by_name(): void
    {
        $this->assertSame([
            'id' => '#',
            'name' => 'Name',
            'secret' => 'Secret',
            'raw' => 'Raw',
        ], (new BaseDataTypeTestElement)->customAttributes());
    }

    /**
     * @covers ::fields
     */
    public function test_fields_returns_default_hidden_id_field(): void
    {
        $field = (new BaseDataType)->fields()->first();

        $this->assertInstanceOf(Hidden::class, $field);
        $this->assertSame('id', $field->get('name'));
    }

    /**
     * @covers ::getFormFields
     */
    public function test_get_form_fields_filters_fields_by_hidden_on(): void
    {
        $fields = (new BaseDataTypeTestElement)->getFormFields('list');

        $this->assertSame(['id', 'name', 'raw'], $fields->map(fn ($field) => $field->get('name'))->values()->all());
    }

    /**
     * @covers ::getFieldsForStore
     * @covers ::getFieldsForUpdate
     */
    public function test_get_fields_for_save_excludes_primary_key_and_raw_fields(): void
    {
        $dataType = new BaseDataTypeTestElement;

        $this->assertSame(['name', 'secret'], $dataType->getFieldsForStore()->map(fn ($field) => $field->get('name'))->values()->all());
        $this->assertSame(['name', 'secret'], $dataType->getFieldsForUpdate()->map(fn ($field) => $field->get('name'))->values()->all());
    }

    /**
     * @covers ::getFieldsForList
     */
    public function test_get_fields_for_list_uses_fields_definition(): void
    {
        $fields = (new BaseDataTypeTestElement)->getFieldsForList();

        $this->assertSame(['id', 'name', 'raw'], $fields->map(fn ($field) => $field->get('name'))->values()->all());
    }

    /**
     * @covers ::actions
     * @covers ::getActions
     * @covers ::hasActions
     */
    public function test_actions_returns_default_actions(): void
    {
        $actions = (new BaseDataTypeTestElement)->getActions();

        $this->assertTrue($actions->contains(fn ($action) => $action instanceof EditAction));
        $this->assertTrue($actions->contains(fn ($action) => $action instanceof ShowAction));
        $this->assertTrue($actions->contains(fn ($action) => $action instanceof DeleteAction));
        $this->assertTrue((new BaseDataTypeTestElement)->hasActions());
    }

    /**
     * @covers ::columns
     * @covers ::getColumns
     * @covers ::getColumnNames
     */
    public function test_columns_returns_field_columns_and_actions_column(): void
    {
        $dataType = new BaseDataTypeTestElement;

        $this->assertSame(['id', 'name', 'raw', 'actions'], $dataType->getColumnNames());
    }

    /**
     * @covers ::getColumnsOrder
     */
    public function test_get_columns_order_returns_columns_with_default_order(): void
    {
        $this->assertSame([[1, 'desc']], (new BaseDataTypeTestElement)->getColumnsOrder());
    }

    /**
     * @covers ::getRepository
     */
    public function test_get_repository_returns_explicitly_assigned_repository(): void
    {
        $this->assertInstanceOf(UserRepository::class, (new BaseDataTypeTestElement)->getRepository());
    }

    /**
     * @covers ::getRepository
     */
    public function test_get_repository_lazily_builds_model_repository_from_declared_model_class(): void
    {
        // DataType без класса-репозитория — достаточно объявить $modelClass, чтобы не
        // писать __construct() только ради `$this->repository = new XRepository`.
        $dataType = new BaseDataTypeTestElementWithoutRepository;

        $repository = $dataType->getRepository();

        $this->assertInstanceOf(ModelRepository::class, $repository);
        $this->assertSame(Redirect::class, $repository->modelClass());
        $this->assertSame($repository, $dataType->getRepository());
    }

    /**
     * @covers ::getModel
     */
    public function test_get_model_works_without_explicit_repository(): void
    {
        $this->assertSame('redirects', (new BaseDataTypeTestElementWithoutRepository)->getModel()->getTable());
    }
}

class BaseDataTypeTestElement extends BaseDataType
{
    protected string $name = 'user';

    protected string $icon = 'bi-user';

    protected string $title = 'Users';

    protected string $singleTitle = 'User';

    protected string $pluralTitle = 'Users';

    protected string $slug = 'custom_users';

    protected string $orderDisplayColumn = 'name';

    protected string $orderDirection = 'asc';

    public function __construct()
    {
        $this->repository = new UserRepository;
    }

    public function layout(): Collection
    {
        return collect([Row::blocks('name')]);
    }

    public function fields(): Collection
    {
        return collect([
            Hidden::make('id')->label('#'),
            Text::make('name')->label('Name')->columnDefaultOrder('desc'),
            Text::make('secret')->label('Secret')->hiddenOn(['list']),
            Text::make('raw')->label('Raw')->set('isRaw', true),
        ]);
    }

    public function rules(Request $request): array
    {
        return [
            'name' => ['required', 'min:3'],
        ];
    }
}

class BaseDataTypeGenerated extends BaseDataType
{
    protected string $name = 'generated';

    protected string $title = 'Generated';

    protected string $orderDisplayColumn = 'name';

    public function __construct()
    {
        $this->repository = new UserRepository;
    }
}

class BaseDataTypeTestElementWithoutRepository extends BaseDataType
{
    protected string $name = 'redirect';

    protected string $title = 'Redirects';

    protected string $slug = 'redirects_without_repository';

    protected string $orderDisplayColumn = 'from';

    protected ?string $modelClass = Redirect::class;
}

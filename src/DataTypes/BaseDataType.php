<?php

namespace KY\AdminPanel\DataTypes;

use AdminPanel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\Str;
use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\Blocks\Col;
use KY\AdminPanel\Blocks\Row;
use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Contracts\RepositoryContract;
use KY\AdminPanel\DataTables\Actions\DeleteAction;
use KY\AdminPanel\DataTables\Actions\EditAction;
use KY\AdminPanel\DataTables\Actions\ShowAction;
use KY\AdminPanel\DataTables\Column;
use KY\AdminPanel\Http\Controllers\BaseDataController;
use KY\AdminPanel\Policies\BasePolicy;
use KY\AdminPanel\FormFields\{Hidden,Field};
use KY\AdminPanel\Traits\HasFormFields;
use KY\AdminPanel\Traits\HasLayout;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BaseDataType implements DataTypeContract
{
    use HasLayout,HasFormFields;

    public RepositoryContract $repository;

    protected string $name;

    protected string $icon = '';

    protected string $title;
    protected string $singleTitle;
    protected string $pluralTitle;

    protected string $slug;

    protected string $orderColumn = 'position';
    protected string $orderDisplayColumn;
    protected string $orderDirection = 'desc'; // ['asc','desc']
    protected string $policy = BasePolicy::class;

    // need for generate routes
    protected string $controller = BaseDataController::class;

//    public function __construct(
//       public RepositoryContract $repository
//    ){
//    }

    public function getName():string{
        return $this->name;
    }
    public function getIcon():string{
        return $this->icon;
    }
    public function getTitle():string{
        return $this->title;
    }

    public function getSingleTitle():string{
        return $this->singleTitle ?? $this->getTitle();
    }
    public function getPluralTitle():string{
        return $this->pluralTitle ?? $this->getTitle();
    }
    public function getTitleForList():string{
        return transForDataTypeTitle($this->getSlug(),'index',['title'=>$this->getPluralTitle()]);
    }
    public function getTitleForForm(bool $edit):string{
        return transForDataTypeTitle($this->getSlug(),$edit?'edit':'create',['title'=>$this->getSingleTitle()]);
    }
    public function getTitleForShow():string{
        return transForDataTypeTitle($this->getSlug(),'show',['title'=>$this->getSingleTitle()]);
    }
    public function getTitleForOrder():string{
        return transForDataTypeTitle($this->getSlug(),'order',['title'=>$this->getPluralTitle()]);
    }

    public function getSlug():string
    {
        if (empty($this->slug)) {
            $name = class_basename($this);

            if (Str::endsWith($name, 'DataType')) {
                $name = substr($name, 0, -strlen('DataType'));
            }

            $this->slug = Str::snake($name);
        }
        return $this->slug;
    }

    public function getModel(){
        return $this->repository->model();
    }
    public function getOrderColumn():string{
        return $this->orderColumn;
    }
    public function getOrderDisplayColumn():string{
        return $this->orderDisplayColumn;
    }
    public function getOrderDirection():string{
        return $this->orderDirection;
    }
    public function getPolicy():string{
        return $this->policy;
    }
    public function getController():string{
        return $this->controller;
    }


    public function getIndexView():string{
        return  'adminpanel::datatypes.common.index';
    }
    public function getFormView():string{
        return  'adminpanel::datatypes.common.form';
    }
    public function getShowView():string{
        return  'adminpanel::datatypes.common.show';
    }
    public function getOrderView():string{
        return  'adminpanel::datatypes.common.order';
    }

    public function showOrderPage():string{
        return  !empty($this->orderColumn) && !empty($this->orderDisplayColumn);
    }

    public function validator(Request $request):Validator
    {
        return ValidatorFacade::make($request->all(), $this->rules($request), $this->messages(), $this->customAttributes());
    }

    public function rules(Request $request):array
    {
        return [];
    }
    public function messages():array
    {
        return [];
    }
    public function customAttributes():array
    {
        $attributes = [];
        foreach ($this->getFields() as $field){
            $attributes[$field->get('name')] = $field->get('label');
        }
        return $attributes;
    }

    public function fields():Collection{
        return collect([
            Hidden::make("id")
                ->label("#")
                ->type('hidden'),
        ]);
    }

    /**
     * @param $type ['list','create','edit','show']
     * @return Collection
     */
    public function getFormFields(string $type):Collection{
        return $this->getFields()->filter(function ($field) use ($type) {
            return empty($field->get('hiddenOn')) || !in_array($type,$field->get('hiddenOn'));
        });
    }
    public function getFieldsForStore():Collection{
        return $this->getFormFields('create')->filter(function ($field){
            return $field->get('name') !== $this->getModel()->getKeyName() && empty($field->get('isRaw'));
        });
    }
    public function getFieldsForUpdate():Collection{
        return $this->getFormFields('edit')->filter(function ($field){
            return $field->get('name') !== $this->getModel()->getKeyName() && empty($field->get('isRaw'));
        });
    }
    public function getFieldsForList():Collection{
        return $this->fields()->filter(function ($field) {
            return empty($field->get('hiddenOn')) || !in_array('list',$field->get('hiddenOn'));
        });
    }

    public function actions(): Collection{
        return collect([
            EditAction::make(),
            ShowAction::make(),
            DeleteAction::make(),
        ]);
    }

    public function getActions(): Collection{
        return $this->actions();
    }

    public function hasActions(): bool{
        return $this->getActions()->isNotEmpty();
    }

    public function columns(): Collection
    {
        $columns = collect([]);
        foreach ($this->getFieldsForList() as $field)
            $columns->push($field->toColumn());

        if($this->hasActions())
            $columns->push(Column::make('actions')->title('Действия')->orderable(false)->searchable(false)->width('5%'));

        return $columns;
    }

    public function getColumns(): Collection
    {
        return $this->columns();
    }

    public function getColumnsOrder(): array
    {
        $order = [];
        foreach ($this->columns() as $key => $column){
            if($column->hasDefaultOrder())
                $order[] = [$key,$column->get('defaultOrder')];
        }
        return $order;
    }

    public function getColumnNames():array{
        $names = [];
        foreach ($this->getColumns() as $column){
            $names[] = $column->get('name');
        }
        return $names;
    }

    public function getDataTable(Request $request): JsonResponse
    {
        $dataTable = DataTables::eloquent($this->repository->getDataTableFilter($request,$this))
            ->addIndexColumn()
            ->orderColumn('id', function ($query, $order) {
                $query->orderBy('id', $order);
            });

        if($this->hasActions())
        {
            $dataTable->editColumn('actions', function ($model) {
                return view('adminpanel::datatables.actions.index', [
                    'model' => $model, 'dataType' => $this
                ]);
            })->rawColumns(['actions']);
        }

        //TODO: remake to column
        foreach ($this->getColumns() as $column)
        {

            if($column->hasField()){
                $field = $column->getField();
                $dataTable->editColumn($field->get('name'), function($model) use ($field)
                {
                    return $field->createContent($this,$model,'cell');
                });
            }

        }

        return $dataTable->make(true);
    }
    public function getDataTablesOptions(): array
    {
        return [
            'processing'=> true,
            'serverSide'=> true,
            'stateSave' => true,
            'ajax' => [
                'url' => route('adminpanel.'.$this->getSlug().'.table'),
//                'data' => []
            ],
            'columns' => $this->getColumns()->toArray(),
            'order' => $this->getColumnsOrder(),
            'columnDefs' => [
                [ 'orderable' => false, 'targets' => 'no-sort','searchable' => false],
                // [ 'searchable' => false, 'targets' => [0]]
            ],
            'orderCellsTop' => true,
            'search' => [
                'regex' => true
            ],
            'language' => [
                'url' => adminpanel_asset('js/datatables/languages/ru_RU.json')
            ],
        ];
    }

}

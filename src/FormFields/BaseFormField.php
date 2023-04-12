<?php

namespace KY\AdminPanel\FormFields;

use AdminPanel,Closure,View,Str;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\FilterContract;
use KY\AdminPanel\DataTables\Column;
use KY\AdminPanel\DataTables\Filters\InputFilter;
use KY\AdminPanel\DataTypes\BaseDataType;
use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Contracts\FormFieldContract;
use KY\AdminPanel\Traits\Attributable;
use KY\AdminPanel\Traits\Makeable;


abstract class BaseFormField implements FormFieldContract, Arrayable
{
    use Makeable, Attributable;

    protected $viewCell;
    protected $viewForm;
    protected $viewShow;
    protected string $slug;
    protected ?FilterContract $filter;
    /**
     * @var array
     */
    protected $attributes = [
        'class' => null,
        'value' => null,
        'name' => null,
        'label' => null,
        'afterLabel'=> null,
        'multilingual' => true,
        'instruction' => null,
        'hiddenOn' => [],
        // TODO: migrate to column
        'columnDefaultOrder' => null, // ['acs','desc']
        'columnOrderable' => true,
        'columnSearchable' => true,
        'columnWidth' =>  null,
        'columnEditable' => false,
    ];

    public function __construct()
    {
        $this->filter = InputFilter::make();
    }

    public function getValue($model)
    {
        return $model->{$this->get('name')};
    }

    public function getLabel(): string
    {
        return $this->get('label');
    }

    /**
     * @param string $label
     * @return $this
     */
    public function label(string $label): self
    {
        return $this->set('label',$label);
    }

    function getAfterLabel() : ?string
    {
        return $this->get('afterLabel',$this->buildAfterLabel());
    }

    function afterLabel(?string $afterLabel): self
    {
        $this->set('afterLabel',$afterLabel);
        return $this;
    }

    function buildAfterLabel(): ?string
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        if (empty($this->slug)) {
            $name = class_basename($this);

            if (Str::endsWith($name, 'FormField')) {
                $name = substr($name, 0, -strlen('FormField'));
            }

            $this->slug = Str::snake($name);
        }

        return $this->slug;
    }

    public function getFilter() : FilterContract
    {
        $this->filter->name($this->filter->get('name')??$this->get('name'));
        $this->filter->placeholder($this->filter->getPlaceholder()??$this->get('label'));
        return $this->filter;
    }

    public function filter(?FilterContract $filter) : self
    {
        $this->filter = $filter;
        return $this;
    }

    public function hasFilter() : bool
    {
        return !empty($this->filter);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return name2id(class_basename($this).'_'.$this->get('name'));;
    }

    /**
     * @return mixed|BaseFormField|null
     */
    public function isMultilingual()
    {
        return $this->get('multilingual',true);
    }

    /**
     * @param bool $multilingual
     * @return $this
     */
    public function multilingual(bool $multilingual): self
    {
        return $this->set('multilingual',$multilingual);
    }

    /**
     * @param string $instruction
     * @return $this
     */
    public function instruction(string $instruction): self
    {
        return $this->set('instruction',$instruction);
    }

    public function hiddenOn(array $hiddenOn): self
    {
        return $this->set('hiddenOn',$hiddenOn);
    }

    function getColumnDefaultOrder() : ?string
    {
        return $this->get('columnDefaultOrder',null);
    }

    public function columnDefaultOrder(?string $columnDefaultOrder = 'asc'): self
    {
        return $this->set('columnDefaultOrder',$columnDefaultOrder);
    }

    function getColumnOrderable() : bool
    {
        return $this->get('columnOrderable',true);
    }

    function columnOrderable(bool $columnOrderable = true) : self
    {
        return $this->set('columnOrderable',$columnOrderable);
    }

    function getColumnSearchable() : bool
    {
        return $this->get('columnSearchable',true);
    }

    function columnSearchable(bool $columnSearchable = true) : self
    {
        return $this->set('columnSearchable',$columnSearchable);
    }

    function getColumnWidth() : ?string
    {
        return $this->get('columnWidth',true);
    }

    function columnWidth(?string $columnWidth) : self
    {
        return $this->set('columnWidth',$columnWidth);
    }

    function columnEditable(bool $columnEditable = true) : self
    {
        return $this->set('columnEditable',$columnEditable);
    }

    function columnIsEditable() : bool
    {
        return  $this->get('columnEditable',false);
    }

    /**
     * @param string $view
     * @return string
     */
    public function viewCell(string $view) : self
    {
        $this->viewCell = $view;
        return $this;
    }

    /**
     * @param string $view
     * @return string
     */
    public function viewForm(string $view) : self
    {
        $this->viewForm = $view;
        return $this;
    }

    /**
     * @param string $view
     * @return string
     */
    public function viewShow(string $view) : self
    {
        $this->viewShow = $view;
        return $this;
    }

    /**
     * @param $view
     * @return mixed
     */
    protected function checkView($view)
    {
        return  View::exists($view);
    }

    /**
     * @param $type
     * @return string
     */
    public function getViewByType($type)
    {
        $view = '';
        switch ($type) {
            case 'cell':
                $view = $this->checkView($this->viewCell)?$this->viewCell:'adminpanel::formfields.'.$this->getSlug().'.cell';
                break;
            case 'form':
                $view = $this->checkView($this->viewForm)?$this->viewForm:'adminpanel::formfields.'.$this->getSlug().'.form';
                break;
            case 'show':
                $view = $this->checkView($this->viewShow)?$this->viewShow:'adminpanel::formfields.'.$this->getSlug().'.show';
                break;
        }
        return $view;
    }

    /**
     * @param DataTypeContract $dataType
     * @param Model $model
     * @param string $viewType
     * @return Application|Factory|View
     */
    public function render($dataType = null, $model = null, string $viewType  = 'form')
    {
        // TODO refactor.
        if(empty($dataType))
            $dataType = app(BaseDataType::class);

        $content = $this->createContent(
            $dataType,
            $model,
            $viewType
        );

        if ($content instanceof View) {
            return $content->render();
        }

        return $content;
    }

    public function beforeCreateContent($dataType, $model): void
    {

    }

    /**
     * @param $field
     * @param $dataType
     * @param $model
     * @param $viewType ['cell','form','show']
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function createContent($dataType, $model, $viewType)
    {
        $this->beforeCreateContent($dataType, $model);

        $field = $this;
        return view($this->getViewByType($viewType), compact(['field','dataType','model']));
    }

    /**
     * @return bool
     */
    public function needSave() : bool
    {
        return true;
    }
    /**
     * Before prepare hook
     * @param $value
     * @return mixed
     */
    public function beforePrepare($value,Request $request,$model){
        return $value;
    }

    /**
     * @param $value
     * @param Request $request
     * @param $model
     * @return mixed|BaseFormField
     */
    public function prepareValue($value, Request $request, $model){
        return empty($value) ? ($this->get('default')??$value) : $value;
    }
    /**
     * After prepare hook
     * @param $value
     * @return mixed
     */
    public function afterPrepare($value,Request $request,$model){
        return $value;
    }

    /**
     * @param Request $request
     * @param $model
     * @return void
     */
    public function beforeSave(Request $request, $model){}

    /**
     * @param Request $request
     * @param $model
     * @return mixed
     */
    public function prepareValueToSave(Request $request, $model){
        $value = $request->input($this->get('name'));

        $value = $this->beforePrepare($value,$request,$model);
        $value = $this->prepareValue($value,$request,$model);
        return $this->afterPrepare($value,$request,$model);
    }

    /**
     * Hook after save model
     * @param Request $request
     * @param $model
     * @return void
     */
    public function afterSave(Request $request, $model){}

    /**
     * @return array
     */
    public function toArray(): array
    {
        return  json_decode(json_encode($this->getAttributes()), true);
    }

    public function toColumn() : Column
    {

        return Column::make($this->get('name'))
            ->title($this->get('label'))
            ->defaultOrder($this->getColumnDefaultOrder())
            ->searchable($this->getColumnSearchable())
            ->orderable($this->getColumnOrderable())
            ->width($this->getColumnWidth())
            ->editable($this->columnIsEditable())
            ->field($this);

    }
}

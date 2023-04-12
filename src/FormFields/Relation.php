<?php

namespace KY\AdminPanel\FormFields;

use Str;
use Illuminate\Database\Eloquent\Model;
use KY\AdminPanel\Contracts\FilterContract;
use KY\AdminPanel\DataTables\Filters\InputFilter;
use KY\AdminPanel\DataTables\Filters\SelectFilter;
use Illuminate\Http\Request;

class Relation extends BaseFormField
{
    protected $attributes = [
        'value' => null,
        'name' => null,
        'label' => null,
        'type' => null,
        'model' => null,
        'relatedModel' => null,
        'table' => null, // related model table
        'column' => null,
        'key' => null,// related model key
        'pivotTable' => null,
        'foreignPivotKey' => null,
        'relatedPivotKey' => null,
        'parentKey' => null,
        'displayedField' => 'name',
        'pivot' => false,
        'taggable' => false,

        'sortField'=>null,
        'sortDirection'=>'asc',
    ];

    public function __construct()
    {
        $this->filter = SelectFilter::make()->handler(null);
    }

    public function getFilter() : FilterContract
    {
        $filter = parent::getFilter();
        $filter->template($filter->get('template','adminpanel::datatables.filters.relation'));

        if(!$filter->hasHandler())
            $filter->setHandler(function ($request, $dataType,$field, $query){
                // TODO: eager load
//                if ($column->isBelongsTo())
//                    $query->with(Str::singular($column->get('table')));
                if ($request->filled($field->get('name'))) {
                    if ($field->isBelongsTo())
                        $query->where($field->get('name'), $request->get($field->get('name')));
                    if ($field->isHasOne() || $field->isHasMany()) {
                        $ids = app($field->get('relatedModel'))->whereIn($field->get('key'), explode(',', $request->get($field->get('name'))))->pluck($field->get('column'));
                        $query->where(app($field->get('model'))->getKeyName(), $ids->toArray());
                    }
                    // TODO: isBelongsToMany
//                    if($column->isBelongsToMany()){
//
//                    }
                }
            });

        return $filter;
    }

    function getColumnOrderable() : bool
    {
        return $this->get('columnOrderable',$this->isBelongsTo());
    }

    public function model($model): self
    {
        $this->set('model', get_class($model));

        return $this;
    }

    public function relatedModel($related): self
    {
        $this->set('relatedModel', get_class($related));
        $this->set('table', $related->getTable());
        $this->set('key', $related->getKeyName());

        return $this;
    }

    public function type($type): self
    {
        $this->set('type', $type);
        return $this;
    }

    public function column($column): self
    {
        $this->set('column', $column);
        return $this;
    }

    public function pivotTable($table): self
    {
        $this->set('pivotTable', $table);
        return $this;
    }

    public function displayedField($field): self
    {
        $this->set('displayedField', $field);
        return $this;
    }

    function required(bool $required = true): self
    {
        return $this->set('required',$required);
    }

    /**
     * @param Model $model
     * @param Model $related
     * @return $this
     */
    public function belongsTo($model, $related): self
    {
        $this->type('belongsTo');
        $this->model($model);
        $this->relatedModel($related);
        $this->column($this->get('name'));
        return $this;
    }
    /**
     * @param Model $model
     * @param Model $related
     * @return $this
     */
    public function hasOne($model, $related): self
    {
        $this->type('hasOne');
        $this->model($model);
        $this->relatedModel($related);
        return $this;
    }
    /**
     * @param Model $model
     * @param Model $related
     * @return $this
     */
    public function hasMany($model, $related): self
    {
        $this->type('hasMany');
        $this->model($model);
        $this->relatedModel($related);
        return $this;
    }

    /**
     * @param Model $model
     * @param Model $related
     * @return $this
     */
    public function belongsToMany($model, $related): self
    {
        $this->type('belongsToMany');
        $this->model($model);
        $this->relatedModel($related);
        $this->makePivotTable($model, $related);
        $this->pivot();
        $this->column('id');
        $this->key('id');
        return $this;
    }

    public function isBelongsTo(): bool
    {
        return $this->get('type') === 'belongsTo';
    }
    public function isHasOne(): bool
    {
        return $this->get('type') === 'hasOne';
    }

    public function isHasMany(): bool
    {
        return $this->get('type') === 'hasMany';
    }

    public function isBelongsToMany(): bool
    {
        return $this->get('type') === 'belongsToMany';
    }

    public function makePivotTable($model, $related): self
    {
        $segments = [
            Str::snake(class_basename($model)),
            Str::snake(class_basename($related))
        ];

        sort($segments);

        $pivotTable = strtolower(implode('_', $segments));

        $this->pivotTable($pivotTable);
        return $this;
    }

    public function needSave() : bool
    {
        return !$this->isBelongsToMany();
    }

    public function afterSave(Request $request, $model)
    {
        if ($this->isBelongsToMany()) {
            $value = $request->input($this->get('name'));

            if (is_array($value)) {
                $value = array_filter($value, function ($item) {
                    return $item !== null;
                });
            }

            $model->belongsToMany(
                $this->get('relatedModel'),
                $this->get('pivotTable'),
                $this->get('foreignPivotKey'),
                $this->get('relatedPivotKey'),
                $this->get('parentKey'),
                $this->get('key')
            )->sync($value);
        }
    }
}

<?php

namespace KY\AdminPanel\DataTables;

use Illuminate\Contracts\Support\Arrayable;
use KY\AdminPanel\Contracts\FormFieldContract;
use KY\AdminPanel\DataTables\Filters\BaseFilter;
use KY\AdminPanel\Traits\Attributable;
use KY\AdminPanel\Traits\HasDynamicCall;
use KY\AdminPanel\Traits\Makeable;

class Column implements Arrayable
{
    use Makeable,Attributable;

    protected $attributes = [
        'name' => '',
        'data' => '',
        'title' => '',
        'searchable' => true,
        'orderable' => true,
        'width' => 'auto',
        'defaultOrder' => null,
        'editable' => false
    ];

    protected ?FormFieldContract $field;

    function name(string $name) : self
    {
        $this->set('name',$name);
        $this->set('data',$name);
        return $this;
    }
    function data(string $data) : self
    {
        return $this->set('data',$data);
    }

    function title(string $title) : self
    {
        return $this->set('title',$title);
    }

    function searchable(bool $searchable) : self
    {
        return $this->set('searchable',$searchable);
    }
    function orderable(bool $orderable) : self
    {
        return $this->set('orderable',$orderable);
    }

    function width(string $width) : self
    {
        return $this->set('width',$width);
    }

    function defaultOrder(?string $defaultOrder) : self
    {
        return $this->set('defaultOrder',$defaultOrder);
    }

    function hasDefaultOrder() : bool
    {
        return !empty($this->get('defaultOrder'));
    }

    function editable(bool $editable) : self
    {
        return  $this->set('editable',$editable);
    }

    function isEditable() : bool
    {
        return  $this->get('editable');
    }

    public function getField() : ?FormFieldContract
    {
        return $this->field;
    }

    /**
     * @param FormFieldContract $field
     * @return self
     */
    public function field(FormFieldContract $field) : self
    {
        $this->field = $field;
        return $this;
    }

    public function hasField() : bool
    {
        return !empty($this->field) && $this->field instanceof FormFieldContract;
    }


    public function toArray(): array
    {
        return json_decode(json_encode($this->getAttributes()), true);
    }
}

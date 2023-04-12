<?php

namespace KY\AdminPanel\DataTables\Filters;

class InputFilter extends BaseFilter
{
    public function __construct()
    {
        $this->setHandler(function ($request, $dataType,$field, $query){
            if ($request->filled($field->get('name')))
            $query->where($field->get('name'), 'like', '%'.$request->get($field->get('name')).'%');
        });
    }

    public function getTemplate(): string
    {
        return $this->get('template','adminpanel::datatables.filters.input');
    }
}

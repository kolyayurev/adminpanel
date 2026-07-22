<?php

namespace KY\AdminPanel\DataTables\Filters;

class InputFilter extends BaseFilter
{
    public function __construct()
    {
        $this->setHandler(function ($request, $dataType, $field, $query) {
            $column = $field->get('name');

            if ($request->filled($column)) {
                $value = $request->get($column);

                if ($this->usesExactMatch($dataType, $column)) {
                    $query->where($column, '=', $value);
                } else {
                    $query->where($column, 'like', '%'.$value.'%');
                }
            }
        });
    }

    public function getTemplate(): string
    {
        return $this->get('template', 'adminpanel::datatables.filters.input');
    }
}

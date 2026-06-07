<?php

namespace KY\AdminPanel\FormFields;

class Coordinates extends BaseFormField
{
    protected $attributes = [
        'class' => null,
        'value' => null,
        'name' => null,
        'label' => null,
        'placeholder' => null,
        'instruction' => null,
        'hiddenOn' => [],

        'columnDefaultOrder' => null, // ['acs','desc']
        'columnOrderable' => true,
        'columnSearchable' => true,
        'columnWidth' => null,
        'columnEditable' => false,

        'storageType' => 'object', // ['object','point']
    ];

    public function getPlaceholder(): string
    {
        return $this->get('placeholder', ap_trans('form-fields.coordinates.placeholder'));
    }

    public function placeholder(string $placeholder): self
    {
        return $this->set('placeholder', $placeholder);
    }

    public function holdAsObject(): self
    {
        return $this->set('storageType', 'object');
    }

    public function holdAsPoint(): self
    {
        return $this->set('storageType', 'point');
    }

    public function prepareValue($value, $request, $model)
    {

        $value = json_decode($value, true);

        $value = match ($this->get('storageType')) {
            'object' => $value,
            // TODO:
            'point' => $value['coords'],
        };

        return empty($value) ? ($this->get('default') ?? $value) : $value;
    }
}

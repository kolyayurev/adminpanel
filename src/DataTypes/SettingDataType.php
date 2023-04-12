<?php

namespace KY\AdminPanel\DataTypes;

use AdminPanel;
use Illuminate\Support\Collection;
use KY\AdminPanel\Repositories\SettingRepository;

class SettingDataType extends BaseDataType
{
    protected string $slug = 'settings';
    protected Collection $fields ;

    public function __construct(){
        $this->fields = collect([]);
        $this->repository = new SettingRepository();
    }

    function setFields(Collection $fields)
    {
        $this->fields = $fields;
    }

    public function getFormFields(string $type):Collection
    {
        return $this->fields->filter(function ($field) use ($type) {
            return empty($field->get('hiddenOn')) || !in_array($type,$field->get('hiddenOn'));
        });
    }
}

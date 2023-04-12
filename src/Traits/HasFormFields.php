<?php
declare(strict_types=1);

namespace KY\AdminPanel\Traits;

use Illuminate\Support\Collection;
use KY\AdminPanel\FormFields\BaseFormField;
use KY\AdminPanel\FormFields\Field;
use KY\AdminPanel\FormFields\Hidden;

trait HasFormFields
{
    public function fields():Collection{
        return collect([]);
    }
    public function getFields():Collection
    {
        return $this->fields()->keyBy(function ($field){ return $field->get('name');});
    }

    public function getField(string $name)
    {
        return $this->fields()->filter(function ($field) use ($name) {
            return $field->get('name') === $name;
        })->first();
    }

    public function getFieldsName():array
    {
        $names = [];
        foreach ($this->fields() as $field) {
            $names[] = $field->get('name');
        }
        return $names;
    }

}

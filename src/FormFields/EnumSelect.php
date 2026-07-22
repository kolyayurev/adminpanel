<?php

namespace KY\AdminPanel\FormFields;

use BackedEnum;

class EnumSelect extends Select
{
    /**
     * Своих вьюх нет — переиспользует cell/form/show обычного Select: к моменту
     * рендера getValue() уже отдаёт скаляр, а не объект enum.
     */
    public function getSlug(): string
    {
        return 'select';
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public function enum(string $enumClass): self
    {
        $this->set('enumClass', $enumClass);

        if (! $this->hasOptions()) {
            $this->options($this->buildOptionsFromEnum($enumClass));
        }

        return $this;
    }

    public function getValue($model)
    {
        $value = parent::getValue($model);

        return $value instanceof BackedEnum ? $value->value : $value;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    protected function buildOptionsFromEnum(string $enumClass): array
    {
        $options = [];

        foreach ($enumClass::cases() as $case) {
            $options[$case->value] = method_exists($case, 'label') ? $case->label() : $case->name;
        }

        return $options;
    }
}

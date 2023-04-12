<?php

namespace KY\AdminPanel\DataTables\Filters;

class SelectFilter extends BaseFilter
{
    protected string $defaultText = 'Выбрать';
    protected $defaultValue = null;
    protected array $options = [];
    protected bool $multiple = false;
    protected bool $ajax = false;

    public function __construct()
    {
        $this->setHandler(function ($request, $dataType,$field, $query){

            if ($request->filled($field->get('name')))
            {
                $items = explode(',',$request->get($field->get('name')));
                foreach ($items as $item)
                    $query->orWhere($field->get('name'), 'like', '%'.$item.'%');
            }
        });
    }

    public function getTemplate(): string
    {
        return $this->get('template','adminpanel::datatables.filters.select');
    }

    /**
     * @return string
     */
    public function getDefaultText(): string
    {
        return trans($this->defaultText);
    }

    /**
     * @param string $defaultText
     * @return SelectFilter
     */
    public function defaultText(string $defaultText): SelectFilter
    {
        $this->defaultText = $defaultText;
        return $this;
    }

    public function hasDefaultText(): bool
    {
        return !empty($this->defaultText);
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     * @return SelectFilter
     */
    public function defaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        if($this->hasDefaultText())
            $this->options = array_merge([$this->getDefaultValue() => $this->getDefaultText()],$this->options);

        return $this->options;
    }

    /**
     * @param array $options
     * @return SelectFilter
     */
    public function options(array $options): SelectFilter
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @param bool $multiple
     * @return SelectFilter
     */
    public function multiple(bool $multiple): SelectFilter
    {
        $this->multiple = $multiple;
        return $this;
    }


    /**
     * @return bool
     */
    public function isAjax(): bool
    {
        return $this->ajax;
    }

    /**
     * @param bool $ajax
     * @return SelectFilter
     */
    public function aAjax(bool $ajax = true): SelectFilter
    {
        $this->ajax = $ajax;
        return $this;
    }

}

<?php

namespace KY\AdminPanel\DataTables\Filters;

use Closure;
use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Contracts\FilterContract;
use KY\AdminPanel\Contracts\FormFieldContract;
use KY\AdminPanel\DataTables\Actions\BaseAction;
use KY\AdminPanel\Traits\HasDynamicCall;
use KY\AdminPanel\Traits\Makeable;

abstract class BaseFilter implements FilterContract
{
    use Makeable,HasDynamicCall;

    protected ?string $name = null;
    protected ?string $placeholder = null;
    protected string $template;
    protected $handler;


    protected array $attributes = [];


    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return BaseFilter
     */
    public function name(?string $name): BaseFilter
    {
        $this->name = $name;
        return $this;
    }

    public function getWidth(): string
    {
        return $this->get('width');
    }

    public function width(string $width): self
    {
        return $this->set('width',$width);
    }

    public function getPlaceholder(): ?string
    {
        return $this->get('placeholder');
    }

    public function placeholder(?string $placeholder): self
    {
        return $this->set('placeholder',$placeholder);
    }


    public function getTemplate(): string
    {
        return $this->get('template');
    }

    public function template(string $template): self
    {
        return $this->set('template',$template);
    }

    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function attributes(array $attributes): BaseAction
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return string
     */
    public function convertAttributesToHtml() :string
    {
        $result = '';

        foreach ($this->getAttributes() as $key => $attribute) {
            $result .= $key.'="'.$attribute.'"';
        }

        return $result;
    }

    /**
     * @param mixed $handler
     * @return BaseFilter
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
        return $this;
    }

    public function hasHandler() : bool
    {
        return $this->handler instanceof Closure;
    }

     public function search(Request $request, DataTypeContract $dataType, FormFieldContract $column, $query) : void
     {
         if($this->hasHandler())
         {
             $handler = $this->handler;
             $handler($request, $dataType,$column, $query);
         }

     }
 }

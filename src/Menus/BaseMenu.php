<?php

namespace KY\AdminPanel\Menus;

use Illuminate\Support\Str;
use KY\AdminPanel\Contracts\MenuContract;
use KY\AdminPanel\Contracts\MenuInterface;

class BaseMenu implements MenuContract
{
    protected string $slug;

    protected string $items;

    public function addItem(){

    }

    public function handle(){

    }
    public function display($type = null, array $options = [])
    {

    }

    public function getSlug():string
    {
        if (empty($this->slug)) {
            $this->slug = Str::snake($this->getName());
        }
        return $this->slug;
    }

    public function getName():string
    {
        $name = class_basename($this);

        if (Str::endsWith($name, 'Menu')) {
            $name = substr($name, 0, -strlen('Menu'));
        }

        return $name;
    }
}

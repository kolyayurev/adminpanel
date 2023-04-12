<?php

namespace KY\AdminPanel\PageTypes;

use Illuminate\Support\{Collection, Str};
use KY\AdminPanel\Blocks\Accordion;
use KY\AdminPanel\Blocks\Collapse;
use KY\AdminPanel\Contracts\PageTypeContract;
use KY\AdminPanel\Traits\HasFormFields;
use KY\AdminPanel\Traits\HasLayout;

class BasePageType implements PageTypeContract
{
    use HasLayout,HasFormFields;

    protected $title;
    protected $slug;

    protected $view = 'adminpanel::settings.index';

    public function getTitle():string
    {
        return $this->title;
    }
    public function getSlug():string
    {
        if (empty($this->slug)) {
            $name = class_basename($this);

            if (Str::endsWith($name, 'PageType')) {
                $name = substr($name, 0, -strlen('PageType'));
            }

            $this->slug = Str::snake($name);
        }

        return $this->slug;
    }

    public function getView():string
    {
        return $this->view;
    }

    /**
     * @return Collection
     */
    public function layout(): Collection
    {
        return collect([
            Accordion::blocks(
                Collapse::blocks(
                    '*'
                )
            )
        ]);
    }
    /**
     * @return Collection
     */
    public function fields():Collection{
        return collect([]);
    }
}

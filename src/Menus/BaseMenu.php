<?php

namespace KY\AdminPanel\Menus;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use KY\AdminPanel\Contracts\CustomPageContract;
use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Contracts\MenuContract;
use KY\AdminPanel\Contracts\PageTypeContract;
use KY\AdminPanel\Facades\AdminPanel;

class BaseMenu implements MenuContract
{
    protected string $slug;

    public function items(): Collection
    {
        return collect([$this->contentGroup(), $this->seoGroup()])
            ->filter()
            ->concat($this->dataTypeItems())
            ->concat($this->customPageItems())
            ->concat(collect([$this->toolsItem()])->filter())
            ->values();
    }

    protected function contentGroup(): ?MenuGroup
    {
        $pageTypes = AdminPanel::getPageTypes();

        if ($pageTypes->isEmpty()) {
            return null;
        }

        $group = new MenuGroup('Контент', 'file-earmark-text');

        /** @var PageTypeContract $pageType */
        foreach ($pageTypes as $pageType) {
            $group->addItem(new MenuItem(
                $pageType->getTitle(),
                route('adminpanel.settings', $pageType->getSlug()),
                active: request('name') === $pageType->getSlug(),
            ));
        }

        return $group;
    }

    protected function seoGroup(): ?MenuGroup
    {
        $group = new MenuGroup('SEO', 'search');

        $entries = [
            ['model' => 'Seo', 'route' => 'adminpanel.seo.index', 'routeIs' => 'adminpanel.seo*', 'title' => 'Мета-информация для страниц'],
            ['model' => 'Redirect', 'route' => 'adminpanel.redirects.index', 'routeIs' => 'adminpanel.redirects*', 'title' => 'Редиректы'],
            ['model' => 'Sef', 'route' => 'adminpanel.sef.index', 'routeIs' => 'adminpanel.sef*', 'title' => 'ЧПУ'],
        ];

        foreach ($entries as $entry) {
            if (! Gate::allows('list', AdminPanel::modelClass($entry['model']))) {
                continue;
            }

            $group->addItem(new MenuItem(
                $entry['title'],
                route($entry['route']),
                active: request()->routeIs($entry['routeIs']),
            ));
        }

        return $group->getItems()->isEmpty() ? null : $group;
    }

    protected function dataTypeItems(): Collection
    {
        return AdminPanel::getDataTypes()
            ->reject(fn (DataTypeContract $dataType) => in_array($dataType->getSlug(), ['seo', 'sef', 'redirects']))
            ->filter(fn (DataTypeContract $dataType) => Gate::allows('list', $dataType->getModel()))
            ->map(fn (DataTypeContract $dataType) => new MenuItem(
                $dataType->getPluralTitle(),
                route('adminpanel.'.$dataType->getSlug().'.index'),
                'collection',
                request()->routeIs('adminpanel.'.$dataType->getSlug().'*'),
            ))
            ->values();
    }

    protected function customPageItems(): Collection
    {
        return AdminPanel::getCustomPages()
            ->filter(fn (CustomPageContract $customPage) => $customPage->showInMenu())
            ->map(fn (CustomPageContract $customPage) => new MenuItem(
                $customPage->getTitle(),
                route('adminpanel.pages.index', $customPage->getSlug()),
                $customPage->getIcon(),
                request()->routeIs('adminpanel.pages.index') && request()->route('page') === $customPage->getSlug(),
            ))
            ->values();
    }

    protected function toolsItem(): ?MenuItem
    {
        if (! Gate::allows('view_tools')) {
            return null;
        }

        return new MenuItem(
            'Tools',
            route('adminpanel.tools.index'),
            'tools',
            request()->routeIs('adminpanel.tools*'),
        );
    }

    public function getSlug(): string
    {
        if (empty($this->slug)) {
            $this->slug = Str::snake($this->getName());
        }

        return $this->slug;
    }

    public function getName(): string
    {
        $name = class_basename($this);

        if (Str::endsWith($name, 'Menu')) {
            $name = substr($name, 0, -strlen('Menu'));
        }

        return $name;
    }
}

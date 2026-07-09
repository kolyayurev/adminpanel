# Menus

Сайдбар админки строится PHP-классом `KY\AdminPanel\Menus\BaseMenu`, а не вьюхой напрямую:
`resources/views/menus/admin.blade.php` просто перебирает результат
`AdminPanel::getMenu('admin')` и рендерит его. Отдельно описывать пункты меню для
[DataType](datatype.md)/[PageType](pagetype.md)/[CustomPage](custom-pages.md) не нужно — они
попадают в меню автоматически при регистрации.

## Как строится меню по умолчанию

`BaseMenu::items()` (класс `KY\AdminPanel\Menus\AdminMenu`, зарегистрирован пакетом под
именем `admin`) собирает, в этом порядке:

- **Контент** — все зарегистрированные `PageType` (`AdminPanel::getPageTypes()`), группа
  видна, если хотя бы один `PageType` зарегистрирован; прав на просмотр не требует.
- **SEO** — встроенные разделы: Мета-информация, Редиректы, ЧПУ. Каждый пункт — только если
  у пользователя есть право `list` на соответствующую модель ([Permissions & Roles](permissions-roles.md)).
- Пункт на каждый прикладной `DataType` (`AdminPanel::getDataTypes()`), кроме служебных
  `seo`/`sef`/`redirects` — с проверкой `list`, заголовок из `getPluralTitle()`.
- Пункт на каждую зарегистрированную [`CustomPage`](custom-pages.md)
  (`AdminPanel::getCustomPages()`), кроме тех, у кого `showInMenu()` возвращает `false`
  (см. [ниже](#getIcon-и-showinmenu)).
- **Tools** — если включён гейт `view_tools` (`config('adminpanel.gates.view_tools')`).

Пункт меню — это `KY\AdminPanel\Menus\MenuItem` (заголовок/URL/иконка/активность), группа
(«Контент», «SEO») — `KY\AdminPanel\Menus\MenuGroup`, коллекция `MenuItem` с автоматическим
`isActive()` (открыта, если активен любой из дочерних пунктов).

## Точки расширения

Три независимых способа, ни один не требует переопределять `admin.blade.php` или вьюху
целиком.

### 1. Добавить свой пункт, не трогая остальное меню

```php
use AdminPanel;
use KY\AdminPanel\Menus\MenuItem;

AdminPanel::addMenuItem(new MenuItem('Отчёты', route('reports.index'), 'bar-chart'));
```

Пункт добавляется в конец меню `admin` независимо от того, чем построена основная часть —
дефолтным `AdminMenu`, наследником или полностью подменённым билдером (см. ниже).

### 2. Унаследоваться от дефолтного билдера

Каждая секция — отдельный `protected`-метод (`contentGroup()`, `seoGroup()`,
`dataTypeItems()`, `customPageItems()`, `toolsItem()`). Переопределите нужный, остальные
останутся как в `AdminMenu`:

```php
namespace App\AdminPanel\Menus;

use KY\AdminPanel\Menus\AdminMenu;
use KY\AdminPanel\Menus\MenuItem;

class MainMenu extends AdminMenu
{
    // Спрятать Tools для этого приложения, не трогая Контент/SEO/DataType/CustomPage.
    protected function toolsItem(): ?MenuItem
    {
        return null;
    }
}
```

```php
// В сервис-провайдере приложения.
AdminPanel::addMenu(MainMenu::class);
```

### 3. Полностью подменить билдер

`MainMenu` (или любой другой класс из `KY\AdminPanel\Contracts\MenuContract`) со слагом
`admin` **заменяет** дефолтный `AdminMenu` целиком — работает по тому же принципу, что и
`AdminPanel::useModel()` для моделей:

```php
namespace App\AdminPanel\Menus;

use Illuminate\Support\Collection;
use KY\AdminPanel\Contracts\MenuContract;

class MainMenu implements MenuContract
{
    public function items(): Collection
    {
        return collect([/* свои MenuItem/MenuGroup */]);
    }

    public function getSlug(): string
    {
        return 'admin'; // тот же слаг, что у AdminMenu — регистрация его заменяет.
    }

    public function getName(): string
    {
        return 'Admin';
    }
}
```

```php
AdminPanel::addMenu(MainMenu::class);
AdminPanel::getMenu('admin'); // Collection<MenuItem|MenuGroup> — items() + пункты из addMenuItem()
```

Пункты, добавленные через `addMenuItem()` (способ 1), домешиваются в результат
`getMenu()` в любом случае — даже если билдер подменён полностью.

## `getIcon()` и `showInMenu()` у CustomPage {#getIcon-и-showinmenu}

`CustomPageContract` даёт два метода специально для меню:

- `getIcon(): string` — имя иконки Bootstrap Icons для пункта меню; дефолт в
  `BaseCustomPage` — `'window'`, переопределяется свойством `$icon`.
- `showInMenu(): bool` — показывать ли страницу в сайдбаре; дефолт `true`. Полезно для
  `CustomPage`, которая существует только как цель для виджета/встраивания в другую страницу
  и не должна занимать место в меню.

```php
class DashboardCustomPage extends BaseCustomPage
{
    protected string $title = 'Дашборд';
    protected string $icon = 'speedometer2';
}

class EmbeddedStatsCustomPage extends BaseCustomPage
{
    protected string $title = 'Встроенная статистика';

    public function showInMenu(): bool
    {
        return false; // открывается только по прямой ссылке/редиректу, не из сайдбара.
    }
}
```

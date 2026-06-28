# Menus

> **Актуальное поведение.** Верхнее меню админки **генерируется автоматически** из
> зарегистрированных [DataType](datatype.md) и [PageType](pagetype.md) — отдельно описывать
> пункты меню не нужно. Класс-абстракция меню (`BaseMenu`/`items()`) и хелпер `menu()` в
> текущей версии **не подключены** к рендерингу (см. ниже).

## Как строится меню сейчас

Меню рендерит вьюха `resources/views/menus/admin.blade.php` (подключена в
`layouts/partials/nav.blade.php`) и собирает пункты из:

- **Контент** — все зарегистрированные `PageType` (`AdminPanel::getPageTypes()`);
- **SEO** — встроенные разделы: Мета-информация, Редиректы, ЧПУ
  (см. [встроенные DataType](built-in-datatypes.md));
- по пункту на каждый прикладной `DataType` (`AdminPanel::getDataTypes()`), кроме служебных
  `seo`/`sef`/`redirects` — заголовок берётся из `getPluralTitle()`;
- **Tools** — если включён гейт `view_tools`.

Видимость пунктов уважает [политики](permissions-roles.md): пункт показывается только если
у пользователя есть право `list` на соответствующую модель (`@can('list', ...)`).

Таким образом, чтобы пункт появился в меню, достаточно **зарегистрировать** DataType/PageType:

```php
AdminPanel::addDataType(PostDataType::class);   // появится пункт «Посты»
AdminPanel::addPageType(AboutPageType::class);  // появится в разделе «Контент»
```

## API меню (зарезервировано)

В пакете есть программный интерфейс меню, который сейчас **не используется** при отрисовке,
но доступен:

```php
use KY\AdminPanel\Menus\BaseMenu;

class MainMenu extends BaseMenu
{
    public function items()
    {
        return collect([ /* пункты */ ]);
    }
}

AdminPanel::addMenu(MainMenu::class);   // регистрация
AdminPanel::getMenu('main');            // получение по slug
```

Хелпер `menu($name, $type)` присутствует в `helpers.php`, но **закомментирован**. Перевод
меню на эту абстракцию (и боковое меню) — предмет отдельной задачи рефакторинга.

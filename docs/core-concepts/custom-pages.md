# CustomPage

`CustomPage` — механизм произвольных страниц **самой админки** (не публичного сайта):
дашборды, отчёты, любые страницы, которые не укладываются в CRUD ([DataType](datatype.md))
или форму настроек ([PageType](pagetype.md)). Типичный пример — дашборд с графиками для
администратора на `/admin/pages/{slug}`.

Страница конструируется PHP-классом и собирается из [виджетов](widgets.md), но раскладка —
**тот же движок [Blocks](blocks.md)/[Layout](layout.md)**, что и у `DataType`/`PageType`
(`Row`/`Col`/`Card`/`Tabs`/`Accordion`). Ничего нового учить не нужно: листья раскладки —
слаги виджетов вместо имён form-field'ов.

## Создание и подключение

Базовый класс — `KY\AdminPanel\CustomPages\BaseCustomPage`:

```php
namespace App\AdminPanel\CustomPages;

use App\AdminPanel\Widgets\PostsPerDayWidget;
use App\AdminPanel\Widgets\UsersGrowthWidget;
use Illuminate\Support\Collection;
use KY\AdminPanel\Blocks\{Row, Col};
use KY\AdminPanel\CustomPages\BaseCustomPage;

class DashboardCustomPage extends BaseCustomPage
{
    protected string $title = 'Дашборд';
    protected string $slug = 'dashboard';

    public function widgets(): Collection
    {
        return collect([
            PostsPerDayWidget::make(),
            UsersGrowthWidget::make(),
        ]);
    }

    public function layout(): Collection
    {
        return collect([
            Row::blocks(
                Col::blocks('posts_per_day')->md(6),
                Col::blocks('users_growth')->md(6),
            ),
        ]);
    }
}
```

Регистрация — через фасад `AdminPanel`, как `PageType`/`DataType`:

```php
use AdminPanel;
use App\AdminPanel\CustomPages\DashboardCustomPage;

public function register(): void
{
    AdminPanel::addCustomPage(DashboardCustomPage::class);
}
```

Страница станет доступна по адресу `/admin/pages/{slug}` (например `/admin/pages/dashboard`)
— это адрес **внутри админки**, за middleware `admin.user`, не публичный роут сайта.

## Состав

- `$title`, `$slug` — заголовок и идентификатор страницы (слаг по умолчанию — snake_case
  имени класса без суффикса `CustomPage`, как у `PageType`/`DataType`).
- `widgets(): Collection` — набор [виджетов](widgets.md) страницы (по умолчанию — пустая
  коллекция).
- `layout(): Collection` — раскладка блоков (см. [Layout](layout.md)/[Blocks](blocks.md)).
  Дефолт из `HasLayout` — `Row::blocks(Col::blocks(Card::blocks('*')))`, `'*'` выводит все
  виджеты страницы без необходимости расписывать раскладку вручную.
- `getIcon(): string` / `showInMenu(): bool` — как страница появляется в сайдбаре (иконка,
  показывать ли вообще); регистрация через `addCustomPage()` уже добавляет пункт меню
  автоматически, руками ничего делать не нужно — подробности в [Menus](menus.md).

## Как это рендерится

`CustomPageController::index()` резолвит страницу по слагу через `AdminPanel::getCustomPage()`
и рендерит её тем же `blocks.layout.index`, что использует `DataType`/`PageType` — просто
листья раскладки резолвятся против `getWidgets()` (виджеты, а не form-field'ы). Для каждого
виджета подключается blade-партиал `adminpanel::widgets.{type}.index`, где `{type}` — это
`$widget->getType()` (см. [Widgets](widgets.md)) — так на странице оказывается, например,
готовый график на Chart.js.

## Асинхронное обновление данных

Данные виджета отдаются **самостоятельным** эндпоинтом, не привязанным к конкретной
`CustomPage` (виджет может показываться на нескольких страницах или использоваться вне их
контекста вообще) — подробности, включая query-параметры/фильтры, см. в
[Widgets](widgets.md#асинхронный-эндпоинт-данных). Регистрация страницы через
`AdminPanel::addCustomPage()` сама регистрирует все её виджеты в общем реестре — отдельно
вызывать `AdminPanel::addWidget()` не нужно, если виджет используется только на этой странице.

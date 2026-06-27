# PageType

`PageType` — это одиночная страница админки (в отличие от [DataType](datatype.md), который
управляет коллекцией записей модели). PageType удобен для разделов вроде «О компании»,
«Контакты», «Настройки сайта» — там, где нужна **одна** форма с набором
[полей](../formfields/list.md), а не CRUD-список.

Базовый класс — `KY\AdminPanel\PageTypes\BasePageType`. По умолчанию страница рендерится
вьюхой `adminpanel::settings.index`, а значения полей хранятся как [настройки](settings.md).

## Создание и подключение

```bash
php artisan adminpanel:make:pagetype About
```

Команда создаёт `App\AdminPanel\PageTypes\AboutPageType`. Подробнее — в разделе
[команды](../getting-started/commands.md).

```php
namespace App\AdminPanel\PageTypes;

use Illuminate\Support\Collection;
use KY\AdminPanel\Blocks\{Accordion, Collapse, Row};
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\PageTypes\BasePageType;

class AboutPageType extends BasePageType
{
    protected string $title = 'About';
    protected string $slug = 'about';

    public static function layout(): Collection
    {
        return collect([
            Accordion::blocks(
                Collapse::blocks(
                    Row::blocks('*')
                ),
            ),
        ]);
    }

    public function fields(): Collection
    {
        return collect([
            Text::make('about_title')->label('Заголовок'),
        ]);
    }
}
```

Регистрация — в сервис-провайдере приложения через фасад `AdminPanel`:

```php
use AdminPanel;
use App\AdminPanel\PageTypes\AboutPageType;

public function register(): void
{
    AdminPanel::addPageType(AboutPageType::class);
}
```

Зарегистрированные PageType автоматически попадают в раздел **«Контент»**
[меню](menus.md) и доступны по маршруту `adminpanel.settings` с их `slug`.

## Состав

- `$title`, `$slug` — заголовок и идентификатор страницы.
- `layout()` — раскладка блоков формы (см. [Layout](layout.md) и [Blocks](blocks.md)).
- `fields()` — набор [полей](../formfields/list.md) страницы.
- `$view` / `getView()` — вьюха страницы (по умолчанию `adminpanel::settings.index`).

## Хранение значений

Значения полей PageType сохраняются через механизм [настроек](settings.md) и читаются
хелпером `setting('about_title')`. Для мультиязычных значений см.
[Multilanguage](multilanguage.md).

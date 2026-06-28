# Breadcrumbs

«Хлебные крошки» в админке построены на пакете
[diglactic/laravel-breadcrumbs](https://github.com/diglactic/laravel-breadcrumbs). Крошки
для маршрутов [DataType](datatype.md) генерируются автоматически.

## Подключение

В `routes/breadcrumbs.php` хост-приложения вызывается:

```php
AdminPanel::breadcrumbsRoutes();
```

Инсталлятор (`adminpanel:install`) добавляет этот вызов автоматически. Он регистрирует крошки
для всех зарегистрированных DataType по шаблону:

```
Dashboard > {Раздел} > index | create | {id} (show) | edit
```

## Конфигурация

```php
// config/adminpanel.php
'breadcrumbs' => [
    'show_dashboard' => true, // включать корневую крошку «Dashboard»
],
```

При `show_dashboard = false` цепочка начинается сразу с раздела, без корневого пункта.

## Свои крошки

Поскольку используется стандартный API `diglactic/laravel-breadcrumbs`, дополнительные крошки
(например, для [PageType](pagetype.md) или кастомных страниц) описываются как обычно:

```php
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('adminpanel.settings', function ($trail, $slug) {
    $trail->parent('adminpanel.dashboard');
    $trail->push('Контент', route('adminpanel.settings', $slug));
});
```

Тексты крошек берутся через переводы (`ap_trans('breadcrumbs.*')`, `transForBread(...)`),
см. [Multilanguage](multilanguage.md).

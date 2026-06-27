# DataType

DataType это один из основных элементов AdminPanel. Он представляет собой обёртку 
вокруг модели, позволяя указывать набор [полей](../formfields/list.md) и их размещение
на странице редактирования в помощью [layout](layout.md).

## Создaние и подключение

```bash
php artisan adminpanel:make:datatype Post Post
```

Подробнее в разделе [команды](../getting-started/commands.md)


## Маршруты

Автоматически генерируются ресурсные маршруты, которые обрабатывает базовый контроллер 
`KY\AdminPanel\Http\Controllers\BaseDataController` 

Для его переопредления вы можете создать новый контроллер, который наследуется от `BaseDataController`

Подробнее в разделе [команды](../getting-started/commands.md) в пункте `Генерация DataController`

## Авторизация

Для настройки прав доступа к маршрутам, используются [политики](https://laravel.com/docs/authorization#writing-policies)
(подробнее — [Permissions & Roles](permissions-roles.md)).
Вы можете написать собственную политику унаследовавшись от  `BasePolicy`
```php
use KY\AdminPanel\Policies\BasePolicy;

class BaseDataType implements DataTypeContract
{
    protected string $policy = BasePolicy::class;
}
```

## Repository

За моделью DataType стоит [репозиторий](repositories.md): он задаёт класс модели, создание
записей и запрос для [списка](datatables.md).

## FormFields

Для указания типов и параметров полей ипользуются [FormFields](../formfields/list.md)

## Layout

Для указания местоположения полей в форме редатирвания ипользуется [layout](layout.md)
(блоки — см. [Blocks](blocks.md)).

## Список (DataTables)

Страница `index` — серверная таблица: колонки, действия, фильтры. См.
[DataTables](datatables.md).

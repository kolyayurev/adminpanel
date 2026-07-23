# DataTables

Списки сущностей (страница `index` у [DataType](datatype.md)) строятся на серверной таблице
поверх [yajra/laravel-datatables](https://yajrabox.com/docs/laravel-datatables/10.0/introduction).
Таблица работает в режиме `serverSide` с пагинацией, сортировкой и поиском на стороне сервера.

## Как формируется таблица

Колонки **не описываются вручную** — они автоматически собираются из
[FormFields](../formfields/list.md), помеченных для показа в списке (видимых на `list`).
Для каждого такого поля вызывается `toColumn()`, плюс в конце добавляется служебная колонка
`Действия` (если у DataType есть actions).

```php
// внутри DataType: поля, видимые в списке, станут колонками
public function fields(): Collection
{
    return collect([
        Text::make('title')->label('Заголовок'),
        Status::make('active')->label('Активность'),
    ]);
}
```

Источник данных для таблицы — запрос из [репозитория](repositories.md)
(`Repository::getDataTableFilter($request, $dataType)`), что позволяет кастомизировать
выборку и фильтрацию.

## Настройка колонки через FormField

Параметры колонки задаются на самом поле (методы с префиксом `column*`):

```php
Text::make('title')
    ->label('Заголовок')
    ->columnSearchable(true)   // поиск по колонке
    ->columnOrderable(true)    // сортировка
    ->columnWidth('20%')       // ширина
    ->columnDefaultOrder('desc') // сортировка по умолчанию
    ->columnEditable(true);    // инлайн-редактирование в ячейке
```

Низкоуровневый объект колонки — `KY\AdminPanel\DataTables\Column` (методы `name`, `data`,
`title`, `searchable`, `orderable`, `width`, `defaultOrder`, `editable`, `field`).

## Действия (Actions)

По умолчанию у каждой строки три действия:

```php
use KY\AdminPanel\DataTables\Actions\{EditAction, ShowAction, DeleteAction};

public function actions(): Collection
{
    return collect([
        EditAction::make(),
        ShowAction::make(),
        DeleteAction::make(),
    ]);
}
```

Действие настраивается флюент-методами `tag`, `icon`, `color`, `title`, `route`,
`policyName`, `template`, `attributes`. Своё действие — наследник
`KY\AdminPanel\DataTables\Actions\BaseAction`. Видимость действия учитывает
[политику](permissions-roles.md) (`policyName`).

## Встроенная таблица и модалки

Таблицу можно вставить в чужую страницу (например, в просмотр записи) компонентом
`<x-adminpanel::datatable>`:

```blade
<x-adminpanel::datatable
    :dataType="$playerItemsDataType"
    :filters="['player_id' => $model->getKey()]"  {{-- залоченный фильтр --}}
    :except="['player_id']"                        {{-- скрыть колонку --}}
    :modal="true"
/>
```

С `:modal="true"` создание, правка и просмотр записи открываются в модальном окне на той же
странице, а не отдельными страницами: кнопки строк ведут на маршруты `{slug}.modal-form` и
`{slug}.modal-show`, которые отдают кусок HTML (`{status, template}`) без обвязки
`layouts.master`. Права проверяются теми же политиками (`create` / `update` / `show`), что и
на полноэкранных маршрутах.

Модальный просмотр берёт тот же шаблон, что и страница, — через «телесную» вьюху DataType:

| Метод | Что отдаёт | Где используется |
| --- | --- | --- |
| `getFormView()` | страница формы целиком | `create`, `edit` |
| `getShowView()` | страница просмотра целиком | `show` |
| `getShowBodyView()` | тело просмотра без `@extends` | страница `show` **и** модалка |

Для формы такой пары нет: модальная форма всегда рисуется шаблоном пакета
(`datatypes/partials/form-body`), переопределение `getFormView()` на неё не влияет —
разметку полей меняют через [layout](layout.md) и `bodyTemplate`.

Чтобы модалка просмотра показывала ваши блоки, переопределите `getShowBodyView()` — стандартная
страница просмотра включает этот же partial, так что дублировать разметку не нужно (если у
DataType свой `getShowView()`, вставьте в него `@include($dataType->getShowBodyView())`):

```php
public function getShowBodyView(): string
{
    return 'admin.players.show-body';
}
```

## Фильтры (Filters)

Фильтр привязывается к полю методом `filter()`:

```php
use KY\AdminPanel\DataTables\Filters\{InputFilter, SelectFilter};

Text::make('title')->filter(InputFilter::make()->placeholder('Поиск по заголовку'));

Select::make('status')->filter(
    SelectFilter::make()
        ->options(['1' => 'Активен', '0' => 'Выключен'])
        ->defaultText('Все')
        ->multiple(false)
);
```

- `InputFilter` — текстовый ввод.
- `SelectFilter` — выпадающий список (`options`, `defaultText`, `defaultValue`,
  `multiple`, ajax-режим).

Свой фильтр — наследник `KY\AdminPanel\DataTables\Filters\BaseFilter` с переопределённым
методом `search(Request $request, DataTypeContract $dataType, FormFieldContract $column, $query)`.

## Опции DataTables

`DataType::getDataTablesOptions()` отдаёт конфиг для фронтенда (`processing`, `serverSide`,
`stateSave`, `ajax.url` → маршрут `adminpanel.{slug}.table`). Эндпоинт таблицы обслуживает
`DataType::getDataTable()`.

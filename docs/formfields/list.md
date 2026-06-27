# FormFields

FormFields описывают поля формы редактирования и колонки [списка](../core-concepts/datatables.md)
в [DataType](../core-concepts/datatype.md) и [PageType](../core-concepts/pagetype.md). Все
поля создаются статическим `::make('имя_поля')` и настраиваются флюентными методами.

```php
use KY\AdminPanel\FormFields\Text;

Text::make('title')
    ->label('Заголовок')
    ->hiddenOn(['list']);
```

## Общие методы (для всех полей)

Доступны на любом поле (определены в `BaseFormField`):

| Метод | Назначение |
|-------|-----------|
| `::make('name')` | создать поле с именем атрибута |
| `->label('Текст')` | подпись поля |
| `->afterLabel('...')` | доп. текст/иконка после подписи |
| `->instruction('...')` | подсказка под полем |
| `->hiddenOn([...])` | скрыть на экранах: `['list','create','update','show']` |
| `->multilingual(true)` | переводимое поле (см. [Multilanguage](../core-concepts/multilanguage.md)) |
| `->filter(InputFilter::make())` | фильтр колонки в списке (см. [DataTables](../core-concepts/datatables.md)) |
| `->columnSearchable(bool)` | поиск по колонке |
| `->columnOrderable(bool)` | сортировка колонки |
| `->columnWidth('20%')` | ширина колонки |
| `->columnDefaultOrder('asc'\|'desc')` | сортировка по умолчанию |
| `->columnEditable(bool)` | инлайн-редактирование в ячейке |
| `->viewCell(...)` / `->viewForm(...)` / `->viewShow(...)` | переопределить вьюхи |

Продвинутые хуки жизненного цикла (для кастомных полей/логики сохранения):
`beforePrepare`, `afterPrepare`, `beforeSave`, `afterSave`, `beforeCreateContent`, `needSave`.

---

## Текстовые

### Text

```php
Text::make('title')
    ->type('number')   // нативный type input: text|number|email|...
    ->default(null)
    ->required();
```

### TextArea

```php
TextArea::make('description')->rows(5);
```

### TextEditor

WYSIWYG-редактор (TinyMCE-подобный):

```php
TextEditor::make('body')
    ->height(400)
    ->contentCss('/css/editor.css')
    ->toolbar2('...');
```

### Password

```php
Password::make('password');
```

### Hidden

Скрытое поле:

```php
Hidden::make('user_id');
```

### Alias

Генерация slug/ЧПУ на основе другого поля:

```php
Alias::make('slug')
    ->source('title')        // из какого поля строить
    ->changeOnTyping(true)   // обновлять при наборе
    ->forceUpdate(false)     // перезаписывать вручную изменённое
    ->route('/blog/');       // префикс маршрута
```

---

## Логические / выбор

### Checkbox

```php
Checkbox::make('active')
    ->default(true)
    ->textOn('Вкл')
    ->textOff('Выкл');
```

### SwitchField

Переключатель (тумблер):

```php
SwitchField::make('published');
```

### Status

Поле-статус (бейдж активности):

```php
Status::make('active')->label('Активность');
```

### Select

```php
Select::make('status')
    ->options(['draft' => 'Черновик', 'published' => 'Опубликовано'])
    ->multiple(false);
```

### Relation

Связь с другой моделью. Тип связи задаётся одним из методов:

```php
Relation::make('category')
    ->belongsTo(Post::class, Category::class)
    ->displayedField('name')   // что показывать пользователю
    ->required();

// другие типы:
Relation::make('tags')->belongsToMany(Post::class, Tag::class);
Relation::make('profile')->hasOne(User::class, Profile::class);
Relation::make('comments')->hasMany(Post::class, Comment::class);
```

Доп. методы: `->column(...)`, `->pivotTable(...)` (для many-to-many).

---

## Дата и время

### Date

```php
Date::make('published_at')->format('Y-m-d');
```

### Timestamp

```php
Timestamp::make('created_at');
```

---

## Координаты

### Coordinates

Карта (Яндекс.Карты, [конфиг `ymaps`](../getting-started/configurations.md)):

```php
Coordinates::make('location')
    ->placeholder('Укажите точку на карте')
    ->holdAsObject();   // хранить как объект {lat,lng}
    // ->holdAsPoint();  // или как точку
```

---

## Медиа

См. также раздел [Media](../core-concepts/media.md).

### Image

Одно изображение с обработкой:

```php
Image::make('cover')
    ->quality(85)
    ->resize(1200, 800);   // ширина, (высота — опционально)
```

### MediaPicker

Выбор файла(ов) через медиа-менеджер:

```php
MediaPicker::make('file')
    ->basePath('documents')
    ->single()                 // один файл вместо множества
    ->hideThumbnails()
    ->allowedTypes(['image/png', 'image/jpeg']);
```

### Gallery

Галерея изображений:

```php
Gallery::make('photos')
    ->min(0)
    ->max(20)
    ->displayValue('alt')
    ->mediaPicker(MediaPicker::make('photos'));
```

### VideoGallery

Галерея видео с превью:

```php
VideoGallery::make('videos')
    ->min(0)
    ->max(10)
    ->videoMediaPicker(MediaPicker::make('video'))
    ->previewMediaPicker(MediaPicker::make('preview'));
```

---

## Списки / повторяющиеся структуры

### ListField

Простой список текстовых элементов:

```php
ListField::make('bullets')->label('Пункты');
```

### ArrayBuilder

Конструктор массива объектов с произвольными под-полями (Element Plus компоненты):

```php
use KY\AdminPanel\FormFields\ArrayBuilder;
use KY\AdminPanel\Support\{ArrayBuilderElement, ArrayBuilderRule};

ArrayBuilder::make('features')
    ->label('Характеристики')
    ->min(0)
    ->max(100)
    ->displayValue('return item.title;')   // тело JS-функции (параметр item)
    ->fields(
        ArrayBuilderElement::make('title')
            ->label('Заголовок')
            ->component('el-input')         // el-input | el-input-number | el-time-select | el-rate | ...
            ->default('')
            ->props(['rows' => 3, 'type' => 'textarea'])
            ->col(24)                        // сетка 24 колонки (Element Plus)
            ->rules(ArrayBuilderRule::make()->required()),
    );
```

`ListField` — это упрощённый `ArrayBuilder` с единственным текстовым полем `text`.

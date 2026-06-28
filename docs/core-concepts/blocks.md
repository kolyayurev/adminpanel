# Blocks

Блоки — это строительные элементы раскладки формы редактирования в [DataType](datatype.md)
и [PageType](pagetype.md). Из блоков собирается `layout()` (см. [Layout](layout.md)), внутрь
блоков помещаются [поля](../formfields/list.md) (по имени) или другие блоки.

Все блоки наследуются от `KY\AdminPanel\Blocks\BaseBlock` и создаются статическим методом
`::blocks(...)`, принимающим вложенные блоки/имена полей. Указание `'*'` выводит все поля.

## Общие методы (BaseBlock)

Доступны на любом блоке (флюентный интерфейс):

- `->class('mt-4')` — CSS-классы блока;
- `->instruction('Подсказка')` — текст-подсказка;
- `->beforeTemplate(...)` / `->afterTemplate(...)` / `->template(...)` — кастомные шаблоны;
- `->visibleOnlyWhenHasFields(true)` — показывать блок только если в нём есть видимые поля.

## Блоки сетки

### Row

```php
use KY\AdminPanel\Blocks\Row;

Row::blocks('field_name', 'other_field');
Row::blocks('*'); // все поля
```

### Col

Колонка bootstrap-сетки с брейкпоинтами:

```php
use KY\AdminPanel\Blocks\Col;

Col::blocks(/* ... */)->xs(12)->sm(6)->md(8)->lg(4);
```

## Контейнеры

### Card

```php
use KY\AdminPanel\Blocks\Card;

Card::blocks(/* ... */)->header('Заголовок')->class('mt-4');
```

### Collapse

Сворачиваемый блок (наследник `Card`, есть `getId()`):

```php
use KY\AdminPanel\Blocks\Collapse;

Collapse::blocks(/* ... */)->header('Заголовок')->instruction('Подсказка');
```

### Accordion

Контейнер для `Collapse`-блоков:

```php
use KY\AdminPanel\Blocks\Accordion;

Accordion::blocks(
    Collapse::blocks(/* ... */)->header('Секция 1'),
    Collapse::blocks(/* ... */)->header('Секция 2'),
);
```

### Tabs / Tab

Вкладки:

```php
use KY\AdminPanel\Blocks\{Tabs, Tab};

Tabs::blocks(
    Tab::blocks(/* ... */)->header('Основное')->id('main'),
    Tab::blocks(/* ... */)->header('SEO')->id('seo'),
)->id('post-tabs');
```

## Вспомогательные

- `Row`, `Col` — сетка;
- `Card`, `Collapse`, `Accordion`, `Tabs`, `Tab` — контейнеры;
- `Divider` — разделитель;
- `Instruction` — блок-подсказка.

Пример сборки полной раскладки — в разделе [Layout](layout.md).

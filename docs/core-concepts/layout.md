# Layout

Основные элементы:

Элементы сетки построены на основе [Bootstrap 5 Grid](https://getbootstrap.com/docs/5.3/layout/grid/)
## Row

```php
use KY\AdminPanel\Blocks\Row;

Row::blocks()

```

## Col

```php
use KY\AdminPanel\Blocks\Col;

Col::blocks()->md(5)->lg(6)

```

## Card

```php
use KY\AdminPanel\Blocks\Card;

Card::blocks()->header('Заголовок')

```

## Accordion

Используется для повещения внутрь `Collapse`
```php
use KY\AdminPanel\Blocks\Accordion;

Accordion::blocks()

```

## Collapse

```php
use KY\AdminPanel\Blocks\Collapse;

Collapse::blocks()->header('Заголовок')->insctuction('Инстуркция')

```

Из элементов собирается общий макет страницы редактирования в DataType и PageType:

```php
...
    public static function layout(): Collection
    {
        return collect([
            Row::blocks(
                Col::blocks(
                    Card::blocks(
                        Row::blocks('field_name')
                    ),
                )->md(8),
                Col::blocks(
                    Card::blocks(
                        Row::blocks('field_name2','field_name3')
                    )->class('mt-4 mt-md-0'),
                    Card::blocks('image')->class('mt-4 ')
                )->md(4),
                Accordion::blocks(
                 Collapse::blocks(
                    // Пустой блок с подсказкой
                 )
                 ->header('Заголовок>')
                 ->instruction('Подсказка'),
                ),
            ),
        ]);
    }
...
```

Указав `'*'` выведутся все поля
```php
...
    public static function layout(): Collection
    {
        return collect([
            Row::blocks('*'),
        ]);
    }
...
```



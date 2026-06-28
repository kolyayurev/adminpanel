# Settings

Настройки — это пары «ключ → значение», которые хранятся в таблице `settings` (модель
`KY\AdminPanel\Models\Setting`) и доступны из любого места приложения. Через настройки
обычно хранятся значения [PageType](pagetype.md) и глобальные параметры сайта
(заголовок, контакты и т.п.).

## Чтение

Хелпер `setting()`:

```php
echo setting('site-title');                 // значение
echo setting('site-title', 'По умолчанию'); // со значением по умолчанию
echo setting('site-title', null, 'en');     // конкретная локаль
```

В blade:

```blade
{{ setting('site-title') }}
```

Под капотом хелпер вызывает `AdminPanel::setting($key, $default, $locale)`.

## Мультиязычность

У модели `Setting` атрибут `value` объявлен переводимым (`$translatable = ['value']`),
поэтому значение настройки можно хранить на нескольких языках. Локаль передаётся третьим
аргументом `setting()`; без неё берётся текущая локаль приложения. См.
[Multilanguage](multilanguage.md).

## Кэширование

В конфиге есть флаг кэширования настроек:

```php
// config/adminpanel.php
'settings' => [
    'cache' => false, // включите, чтобы кэшировать разрешённые значения
],
```

После изменения значений при включённом кэше может потребоваться очистка кэша.

## Управление

Редактирование настроек доступно через встроенный раздел `settings`
(`SettingDataType`) — см. [встроенные DataType](built-in-datatypes.md). Поля настроек
описываются так же, как и в обычных DataType/PageType, через [FormFields](../formfields/list.md).

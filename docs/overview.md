# Обзор kolyayurev/adminpanel

`kolyayurev/adminpanel` — переиспользуемый **Laravel-пакет** административной панели
(namespace `KY\AdminPanel\`). Пакет подключается в хост-приложение и даёт готовый CRUD,
управление контентом, медиа, правами и SEO. Своего app-скелета у пакета нет — он работает
внутри Laravel-приложения.

Этот документ — карта возможностей и точка входа в документацию. Полная навигация —
в [summary.md](summary.md).

## Стек

- **PHP 8.4**, **Laravel 13** (`illuminate/* ^13.0`).
- UI: Blade + Bootstrap 5, интерактивные поля — Vue + [Element Plus](core-concepts/element-plus.md).
- Списки: [yajra/laravel-datatables](core-concepts/datatables.md) (server-side).
- Медиа: `intervention/image`, `gregwar/image`.
- Крошки: `diglactic/laravel-breadcrumbs`.
- Иконки: Bootstrap Icons.

## Ключевые концепции

| Концепция | Что делает |
|-----------|-----------|
| [DataType](core-concepts/datatype.md) | CRUD-обёртка над моделью: список, форма, права, маршруты |
| [PageType](core-concepts/pagetype.md) | Одиночная страница-форма (значения хранятся как настройки) |
| [CustomPage](core-concepts/custom-pages.md) | Произвольная страница админки (дашборд и т.п.) из [виджетов](core-concepts/widgets.md) |
| [Widgets](core-concepts/widgets.md) | Виджет с данными/графиком (Chart.js) и async-обновлением |
| [Repository](core-concepts/repositories.md) | Модель за DataType + запрос для списка/фильтрации |
| [FormFields](formfields/list.md) | 22 типа полей формы и колонок (текст, медиа, связи, списки…) |
| [Blocks](core-concepts/blocks.md) / [Layout](core-concepts/layout.md) | Раскладка формы редактирования из блоков |
| [DataTables](core-concepts/datatables.md) | Серверные таблицы: колонки, действия, фильтры |
| [Menus](core-concepts/menus.md) | Сайдбар: автогенерация из DataType/PageType/CustomPage + точки расширения |
| [Settings](core-concepts/settings.md) | Глобальные настройки `setting('key')` |
| [Media](core-concepts/media.md) | Загрузка файлов, превью, медиа-менеджер |
| [Permissions & Roles](core-concepts/permissions-roles.md) | Роли, права, политики, гейты |
| [Multilanguage](core-concepts/multilanguage.md) | Переводимые модели и поля |
| [Breadcrumbs](core-concepts/breadcrumbs.md) | Автоматические «хлебные крошки» |

## Что есть из коробки

- [Встроенные DataType](core-concepts/built-in-datatypes.md): Пользователи, Роли, SEO,
  Редиректы, ЧПУ, Настройки.
- Консольные [команды-генераторы](getting-started/commands.md) DataType/PageType/
  Repository/DataController.
- Медиа-менеджер, мультиязычность, права на основе политик.

## С чего начать

1. [Установка](getting-started/installation.md) → [Конфигурация](getting-started/configurations.md).
2. Создать сущность: [команды](getting-started/commands.md) →
   [DataType](core-concepts/datatype.md) + [Repository](core-concepts/repositories.md).
3. Описать [поля](formfields/list.md) и [раскладку](core-concepts/layout.md).

## Для разработчиков пакета и AI

Технический контекст рефакторинга (модернизация под Laravel 13 / PHP 8.4): тест-конвенции,
текущее покрытие и список известных рисков совместимости — в
[`laravel-13-php-84-ai-context.md`](laravel-13-php-84-ai-context.md). Этот файл —
источник правды по тестам; дублировать его содержимое в других доках не нужно, ссылайтесь.

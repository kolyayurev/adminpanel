# Changelog

Все значимые изменения пакета `adminpanel` фиксируются здесь.

Формат основан на [Keep a Changelog](https://keepachangelog.com/ru/1.1.0/),
проект следует [семантическому версионированию](https://semver.org/lang/ru/).
Пока пакет в стадии `0.x`: ломающие изменения поднимают **minor** (0.7 → 0.8),
фичи и фиксы — **patch**. Релиз `1.0.0` — после полного снятия legacy-стека
(jQuery / Bootstrap JS, T12–T13).

## [Unreleased]

Модернизация фронтенда админки под Laravel 13 / PHP 8.4: сборка на Vite, интерактив
переезжает на Element Plus + Vue 3, снимаются библиотеки jQuery-эпохи. Нетто −650 строк,
новых зависимостей нет.

### Changed
- Сборка ассетов: laravel-mix → **Vite** (T03).
- Bootstrap: alpha → stable 5.3.x (T06).
- Нотификации и диалоги — на **Element Plus** вместо toastr/SweetAlert2; внешний API
  `toastr`/`Swal` сохранён (T07).
- Form controls (select / relation / date / фильтры таблиц) — на Element Plus вместо
  select2; datepicker с учётом локали (T08).
- Таблицы и логи — на Element Plus `el-table` (blade-rendered) вместо DataTables (T09).
- Загрузка медиа в `MediaManager` — на `el-upload` вместо Dropzone (T10).

### Removed
- Зависимости: `select2` (+ theme), `datatables.net-*`, `sweetalert2`, `toastr`,
  `dropzone`, `moment`, `bootstrap-fileinput`, плюс ранее снятые мёртвые фронт-deps (T05).
- `bootstrap-fileinput` и `moment` — оказались мёртвым кодом (инициализация не вызывалась /
  глобал без потребителей); поведение не изменилось.
- Глобалы `window.Cropper` / `window.Dropzone`. `cropperjs` оставлен (у Element Plus нет
  своего кроппера), импортируется локально в `MediaManager`.

### Fixed
- Vite: раздельная эмиссия CSS-входов; импорт i18n json (default-import для `lang.js`).
- `Select::prepareValue` — двойное кодирование одиночного значения.
- Admin CRUD save на PostgreSQL: виртуальные password-поля (`dontSave`), ignore unique-rule
  при создании.
- Мост мультиязычности для `el-select`/date-полей; guard сохранения перевода `Setting`.
- Docs viewer: корректный route в JS-карте; стили highlight.js.

## [0.6] — init

- Исходная версия пакета (тег `0.6` на первом коммите; до начала модернизации).

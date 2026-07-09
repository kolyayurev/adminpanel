# Changelog

Все значимые изменения пакета `adminpanel` фиксируются здесь.
Формат — [Keep a Changelog](https://keepachangelog.com/ru/1.1.0/),
версионирование — [SemVer](https://semver.org/lang/ru/) (стадия `0.x`).

## [Unreleased]

## [0.7] — 2026-07-09

Модернизация фронтенда админки под Laravel 13 / PHP 8.4.

### Changed
- Сборка ассетов переведена на Vite.
- Интерфейс переведён на Vue 3 + Element Plus (нотификации, формы, таблицы, загрузка медиа).
- Меню — боковой сворачиваемый sidebar.
- Ссылки на ассеты версионируются: после обновления админки браузер сам подхватывает
  свежие стили и скрипты, без ручного сброса кэша.

### Removed
- Устаревшие фронтенд-зависимости: select2, datatables.net, sweetalert2, toastr, dropzone,
  bootstrap-fileinput, moment.

## [0.6]

- Исходная версия пакета.

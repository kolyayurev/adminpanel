# Обновление

Обновление пакета `kolyayurev/adminpanel` в хост-приложении. История изменений —
в [`CHANGELOG.md`](../../CHANGELOG.md).

## Шаги

1. Обновите зависимость через Composer:

   ```bash
   composer update kolyayurev/adminpanel
   ```

2. Переопубликуйте ассеты (стили/скрипты пакета могли измениться):

   ```bash
   php artisan vendor:publish --tag="adminpanel-assets" --force
   ```

3. Если в релизе появились новые миграции — выполните их:

   ```bash
   php artisan migrate
   ```

4. При необходимости переопубликуйте конфиг и переводы и сверьте их со своими правками
   (флаг `--force` перезапишет ваши изменения — сначала сделайте бэкап/diff):

   ```bash
   php artisan vendor:publish --tag="adminpanel-config" --force
   php artisan vendor:publish --tag="adminpanel-lang" --force
   ```

5. Сбросьте кэши:

   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

## Требования

Актуальная версия требует **PHP 8.4** и **Laravel 13** (`illuminate/* ^13.0`). Перед
обновлением убедитесь, что хост-приложение соответствует этим требованиям.

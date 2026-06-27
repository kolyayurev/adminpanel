# Media

Работа с файлами и изображениями в админке построена вокруг сервиса
`KY\AdminPanel\Support\APMedia` (доступен также через фасад/алиас `APMedia`). Он сохраняет
файлы на сконфигурированный диск, генерирует превью (thumbnails) и отдаёт URL. Под капотом —
`intervention/image` и `gregwar/image`.

С медиа связаны [поля](../formfields/list.md) `Image`, `Gallery`, `VideoGallery` и
`MediaPicker`, а также встроенный медиа-менеджер.

## Конфигурация

```php
// config/adminpanel.php
'storage' => [
    'disk' => env('FILESYSTEM_DRIVER', 'public'),
],

'media' => [
    'allowed_mimetypes'   => '*',     // '*' или массив mimetypes
    'path'                => '/',     // базовый путь на диске
    'show_folders'        => true,
    'allow_upload'        => true,
    'allow_move'          => true,
    'allow_delete'        => true,
    'allow_create_folder' => true,
    'allow_rename'        => true,
    'default_thumb_name'  => 'thumb', // суффикс генерируемых превью
],
```

Для диска `public` не забудьте `php artisan storage:link` (инсталлятор делает это сам).
Доступ к медиа-менеджеру управляется гейтом `view_media`.

## Основные методы APMedia

Сохранение:

- `storeAs($file, string $folder = '/', array $settings = []): string` — сохранить файл;
- `saveImageFromFile($file, string $folder = 'images', array $settings = []): string` —
  сохранить изображение из загруженного файла;
- `saveImage($image, string $path, array $settings = []): string` — сохранить изображение.

Превью и обработка:

- `thumbnail(string $filePath, Trumbnail $thumbnail): string`
  / `thumbnailByArray(string $filePath, array $thumbnail): string`;
- `scale(...)`, `crop(...)`, `resize(...)`, `fit(...)` — трансформации с опциональным суффиксом.

URL и удаление:

- `getUrl(?string $path, string $default = ''): string`;
- `getImageThumbUrl(?string $path, ?string $suffix = null): string`
  / `getImageThumb(...)`;
- `deleteImage(string $removeImage)`;
- `preparePath(...)`, `getExtFromPath(...)` — вспомогательные.

## Превью и водяные знаки

- `KY\AdminPanel\Support\Trumbnail` — описание превью (размеры/режим), передаётся в
  `thumbnail()`.
- `KY\AdminPanel\Support\Watermark` — наложение водяного знака на изображение.

## Связанные поля

- `Image` — одно изображение (с превью);
- `Gallery` / `VideoGallery` — галереи;
- `MediaPicker` — выбор файла через медиа-менеджер.

Подробности по параметрам полей — в [списке FormFields](../formfields/list.md).

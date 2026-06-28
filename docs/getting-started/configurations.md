# Configurations

With the installation you will find a new configuration file located at `config/adminpanel.php`
(published from the package `config/config.php`).
In this file you can find various options to change the configuration of your installation.

If you cache your configuration files please make sure to run `php artisan config:clear` after
you changed something.

Below we describe every configuration section. Most options are also bound to environment
variables (`AP_*`) so you can override them per environment without editing the file.

## General

```php
'prefix' => env('AP_PREFIX', 'admin'),   // URL prefix of the admin panel (e.g. /admin)
'name'   => env('AP_NAME', 'AP'),         // short application name shown in the UI
```

**prefix:** route prefix under which all admin routes are registered (`AdminPanel::routes()`
is wrapped in `Route::group(['prefix' => config('adminpanel.prefix')], ...)`).
**name:** the short name used as the default logo/title.

## Redirects

```php
'redirects' => [
    'dashboard' => false, // false or '/url'
],
```

**dashboard:** where to send the user when opening the panel root. `false` keeps the default
dashboard; set a path to redirect somewhere else.

## Interface (theme / navbar / breadcrumbs)

```php
'theme' => env('AP_THEME', 'light'), // bootstrap theme mode: ['light','dark']

'navbar' => [
    'logo' => env('AP_NAME', 'AP'),
    'url'  => '/'.env('AP_PREFIX', 'admin'),
],

'breadcrumbs' => [
    'show_dashboard' => true, // include the dashboard crumb at the start of the trail
],
```

## Permissions and gates

```php
'gates' => [
    'view_tools' => env('AP_GATES_VIEW_TOOLS', false),
    'view_dev'   => env('AP_GATES_VIEW_DEV', false),
    'view_media' => env('AP_GATES_VIEW_MEDIA', true),
],
```

Feature gates that toggle access to built-in tools. **view_tools** — the Tools section,
**view_dev** — developer-only tools, **view_media** — the media manager.

## Users

```php
'user' => [
    'add_default_role_on_register' => true,
    'default_role'                 => 'user',
    'redirect'                     => '/'.env('AP_PREFIX','admin'),
],
```

**add_default_role_on_register:** whether to attach the default role to any newly created user.
**default_role:** the slug of that default role.
**redirect:** redirect path after the user logged in.

## Multilingual

```php
'multilingual' => [
    'enabled' => false,   // enable multilingual support for form fields
    'default' => 'ru',    // default locale
    'locales' => ['ru', 'en'],
],
```

Controls translatable form fields and content. See [Multilanguage](../core-concepts/multilanguage.md).

## Yandex Maps

```php
'ymaps' => [
    'key'    => env('AP_YMAPS_KEY', ''),
    'center' => [
        'lat' => env('AP_YMAPS_DEFAULT_CENTER_LAT', '59.93499'),
        'lng' => env('AP_YMAPS_DEFAULT_CENTER_LNG', '30.31907'),
    ],
    'zoom'   => env('AP_YMAPS_DEFAULT_ZOOM', 8),
],
```

Used by the `Coordinates` form field. **key** — Yandex Maps API key; **center**/**zoom** —
default map position when no coordinates are set yet.

## Additional stylesheets

```php
'additional_css' => [
    //'css/custom.css',
],
```

You can add your own custom stylesheets that will be included in the admin dashboard. This
means you could technically create a whole new theme by adding your own custom stylesheet.

Read more [here](../customization/additional-css-js.md). The path will be passed to Laravel's
[asset](https://laravel.com/docs/helpers#method-asset) function.

## Additional Javascript

```php
'additional_js' => [
    //'js/custom.js',
],
```

The same goes for this configuration. You can add your own javascript that will be executed in
the admin dashboard. Add as many javascript files as needed.
Read more [here](../customization/additional-css-js.md).

## Icons

```php
'icons' => [
    'bi' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css',
],
```

Source of the icon font used across the UI. See [Icons](../core-concepts/icons.md).

## Controls panel

```php
'controls_panel' => [
    'enable'   => true,
    'position' => 'top-right', // ['top-left','bottom-left','top-right','bottom-right']
],
```

The floating front-end admin controls panel (quick edit links shown on the public site for
logged-in admins). **enable** toggles it; **position** sets the screen corner.

## Settings

```php
'settings' => [
    'cache' => false, // cache resolved settings values
],
```

See [Settings](../core-concepts/settings.md).

## Storage

```php
'storage' => [
    'disk' => env('FILESYSTEM_DRIVER', 'public'),
],
```

The filesystem disk used for uploaded media. Run `php artisan storage:link` so uploads on the
`public` disk are reachable (the installer does this for you).

## Media

```php
'media' => [
    'allowed_mimetypes'   => '*', // '*' = any, or an array of mimetypes
    'path'                => '/', // base path relative to the storage disk
    'show_folders'        => true,
    'allow_upload'        => true,
    'allow_move'          => true,
    'allow_delete'        => true,
    'allow_create_folder' => true,
    'allow_rename'        => true,
    'default_thumb_name'  => 'thumb',
],
```

Media-manager behaviour and permissions. **allowed_mimetypes** restricts uploads;
**default_thumb_name** is the suffix used for generated thumbnails.

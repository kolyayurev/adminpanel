# Installation

To install

```bash
php artisan adminpanel:install
```

The installer (`adminpanel:install`) performs the following steps:

- runs `php artisan migrate` to create the admin panel tables;
- patches `app/Models/User.php` to extend `KY\AdminPanel\Models\User` (if the file is in a
  non-standard location you have to change `extends Authenticatable` manually);
- registers routes in `routes/web.php` (`AdminPanel::routes()` under the configured prefix)
  and breadcrumbs in `routes/breadcrumbs.php` (`AdminPanel::breadcrumbsRoutes()`);
- seeds the default data (`AdminPanelDatabaseSeeder`);
- publishes the config to `config/adminpanel.php` (tag `adminpanel-config`);
- creates the storage symlink (`php artisan storage:link`) and clears the cache.

A user has been created for you with the following login credentials:

> **email:** `admin@admin.com`  
> **password:** `admin`

После установки, вы можете начать создавать основные сущности через [консольные команды](commands.md)

You can publish the assets with:

```bash
php artisan vendor:publish --tag="adminpanel-assets"
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="adminpanel-config"
```

You can publish the migration files with:

```bash
php artisan vendor:publish --tag="adminpanel-migrations"
```

You can publish the lang files with:

```bash
php artisan vendor:publish --tag="adminpanel-lang"
```


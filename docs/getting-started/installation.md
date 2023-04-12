# Installation

To install

```bash
php artisan adminpanel:install
```

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


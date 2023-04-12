# Overriding files

## Overriding Views

You can override any views, see [docs](https://laravel.com/docs/master/packages#overriding-package-views)

## Overriding Models

You are also able to override models if you need to.  
To do so, you need to add the following to your AppServiceProviders register method:

```php
AdminPanel::useModel($name, $object);
```

Where **name** is the class-name of the model and **object** the fully-qualified name of your custom model. For example:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Events\Dispatcher;
use KY\AdminPanel\Facades\AdminPanel;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        AdminPanel::useModel('Role', \App\Models\Role::class);
    }
    // ...
}
```

The next step is to create your model and make it extend the original model. In case of `Role`:

```php
<?php

namespace App;

class Role extends \KY\AdminPanel\Models\Role
{
    // ...
}
```




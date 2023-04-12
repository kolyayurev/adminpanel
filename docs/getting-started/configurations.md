# Configurations

With the installation you will find a new configuration file located at `config/adminpanel.php`.  
In this file you can find various options to change the configuration of your installation.

If you cache your configuration files please make sure to run `php artisan config:clear` after you changed something.

Below we will take a deep dive into the configuration file and give a detailed description of each configuration set.

## Users

```php
<?php

'user' => [
    'add_default_role_on_register' => true,
    'default_role'                 => 'user',
    'redirect'                     => '/'.env('AP_PREFIX','admin'),
],
```

**add\_default\_role\_on\_register:** Specify whether you would like to add the default role to any new user that is created.  
**default\_role:** You can also specify what the **default\_role** is of the user.  
**redirect:** Redirect path after the user logged in.

## Additional stylesheets

```php
<?php

'additional_css' => [
    //'css/custom.css',
],
```

You can add your own custom stylesheets that will be included in the admin dashboard. This means you could technically create a whole new theme by adding your own custom stylesheet.

Read more [here](../customization/additional-css-js.md).

The path will be passed to Laravels [asset](https://laravel.com/docs/helpers#method-asset) function.

## Additional Javascript

```php
<?php

'additional_js' => [
    //'js/custom.js',
],
```

The same goes for this configuration. You can add your own javascript that will be executed in the admin dashboard. Add as many javascript files as needed.

Read more [here](../customization/additional-css-js.md).

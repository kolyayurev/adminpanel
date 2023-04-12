# Additional CSS and JS

You can add additional CSS and JS files to the master blade without having to copy or modify the blade file itself removing potential manual migration headaches later on. The CSS and JS files are added _after_ any admin assets so you can override styles and functionality comfortably.

This is all handled via the `adminpanel.php` config file:

```php
// Here you can specify additional assets you would like to be included in the master.blade
'additional_css' => [
    //'css/custom.css',
],
'additional_js' => [
   //'js/custom.js',
],
```

The path will be passed to Laravels [asset](https://laravel.com/docs/helpers#method-asset) function.



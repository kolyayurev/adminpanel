# Menus 

* [Menus](core-concepts/menus.md)
Anywhere you wanted to display your menu you can now call:

```php
menu('main', 'my_menu');
```

And your custom menu will now be output.

## Menu as JSON

If you dont want to render your menu but get an array instead, you can pass `_json` as the second parameter. For example:

```php
menu('main', '_json')
```

This will give you a collection of menu-items.


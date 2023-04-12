# Overriding Routes

You can override any Admin Panel routes by writing the routes you want to overwrite below `AdminPanel::routes()`. For example if you want to override your post LoginController:

```php
Route::group(['prefix' => config('adminpanel.prefix')], function () {
   AdminPanel::routes();

   // Your overwrites here
   Route::post('login', ['uses' => 'MyAuthController@postLogin', 'as' => 'postlogin']);
});
```


# Permissions & Roles

Доступ в админке строится на **ролях** и **правах** (permissions), а проверки выполняются
через стандартные [политики Laravel](https://laravel.com/docs/authorization#writing-policies)
и гейты.

## Модели

- `KY\AdminPanel\Models\Role` — роль. Связи: `users()`, `permissions()` (many-to-many).
- `KY\AdminPanel\Models\Permission` — право (по полю `key`). Связь `roles()`; статический
  хелпер `Permission::check($key)` — существует ли такое право.

Пользователь подключает трейт `KY\AdminPanel\Traits\AdminPanelUser` (инсталлятор меняет
`App\Models\User` на наследника `KY\AdminPanel\Models\User`). Трейт даёт методы:

```php
$user->role();                 // основная роль
$user->roles();                // роли пользователя
$user->hasRole('admin');       // проверка роли
$user->setRole('admin');       // назначить роль
$user->hasPermission('edit');  // проверка права
$user->hasPermissionOrFail('edit');         // бросит исключение
$user->hasPermissionOrAbort('edit', 403);   // abort(403)
```

Роль по умолчанию для новых пользователей настраивается в конфиге
(`user.add_default_role_on_register`, `user.default_role`).

## Политики (Policies)

Базовая политика — `KY\AdminPanel\Policies\BasePolicy` с методами:

```php
list($user)                 // доступ к списку
show($user, $model)         // просмотр записи
create($user)               // создание
update($user, $model)       // редактирование
delete($user, $model)       // удаление
restore($user, $model)      // восстановление
forceDelete($user, $model)  // полное удаление
```

Каждый [DataType](datatype.md) указывает свою политику через свойство `$policy`
(по умолчанию `BasePolicy`). Своя политика — наследник `BasePolicy`:

```php
use KY\AdminPanel\Policies\BasePolicy;

class PostPolicy extends BasePolicy
{
    public function update($user, $model)
    {
        return $user->hasPermission('posts.edit');
    }
}
```

```php
// в DataType
protected string $policy = PostPolicy::class;
```

Проверки в blade/коде — как обычно в Laravel:

```blade
@can('list', AdminPanel::modelClass('Seo'))
    {{-- пункт меню --}}
@endcan
```

Именно так [меню](menus.md) скрывает пункты, недоступные пользователю.

## Гейты (feature gates)

Помимо политик, часть возможностей закрыта гейтами (см. [конфиг](../getting-started/configurations.md)):

- `view_tools` — раздел Tools;
- `view_dev` — инструменты разработчика;
- `view_media` — медиа-менеджер.

```blade
@can('view_tools')
    {{-- ссылка на Tools --}}
@endcan
```

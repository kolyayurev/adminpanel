# Встроенные DataType

Пакет поставляется с набором готовых [DataType](datatype.md) для типовых задач. Они
регистрируются автоматически и доступны в [меню](menus.md) (с учётом
[прав](permissions-roles.md)).

| Slug | Раздел | Назначение |
|------|--------|-----------|
| `users` | Пользователи | Управление пользователями админки |
| `roles` | Роли | Роли и привязанные к ним [права](permissions-roles.md) |
| `seo` | Мета-информация для страниц | SEO-метаданные (title/description и пр.) для URL |
| `redirects` | Редиректы | Управление HTTP-редиректами |
| `sef` | ЧПУ | Человекопонятные URL (slug ↔ маршрут) |
| `settings` | Настройки | Глобальные [настройки](settings.md) сайта |

## Соответствующие классы

- `KY\AdminPanel\DataTypes\UserDataType` (модель `User`)
- `KY\AdminPanel\DataTypes\RoleDataType` (модель `Role`)
- `KY\AdminPanel\DataTypes\SeoDataType` (модель `Seo`)
- `KY\AdminPanel\DataTypes\RedirectDataType` (модель `Redirect`)
- `KY\AdminPanel\DataTypes\SefDataType` (модель `Sef`)
- `KY\AdminPanel\DataTypes\SettingDataType` (настройки)

В [меню](menus.md) разделы `seo`, `sef`, `redirects` сгруппированы под пунктом **SEO** и
не дублируются в общем списке DataType.

## Кастомизация

Любой встроенный DataType можно переопределить в приложении: создать свой класс-наследник
и зарегистрировать его вместо стандартного через `AdminPanel::addDataType(...)`. Модели тоже
можно подменить (`AdminPanel::useModel(...)`). Маршруты и поведение наследуются от
[BaseDataType](datatype.md) / `BaseDataController`.

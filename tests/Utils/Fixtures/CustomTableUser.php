<?php

namespace KY\AdminPanel\Tests\Utils\Fixtures;

use KY\AdminPanel\Models\User;

/**
 * Модель-наследник с нестандартной таблицей — для проверки, что валидация email
 * берёт таблицу из модели, а не из литерала.
 */
class CustomTableUser extends User
{
    protected $table = 'admin_users';
}

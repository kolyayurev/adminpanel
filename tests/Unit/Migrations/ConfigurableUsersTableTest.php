<?php

namespace KY\AdminPanel\Tests\Unit\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use KY\AdminPanel\Tests\TestCase;

/**
 * Миграции пакета должны работать с таблицей пользователей из config('adminpanel.users_table').
 */
class ConfigurableUsersTableTest extends TestCase
{
    public function test_users_table_config_defaults_to_users(): void
    {
        $this->assertSame('users', config('adminpanel.users_table'));
    }

    public function test_migrations_target_configured_users_table(): void
    {
        config()->set('adminpanel.users_table', 'admin_users');

        // Хост держит свою таблицу админов; user_role уже создан в setUp с дефолтом — пересоздадим.
        Schema::create('admin_users', fn (Blueprint $t) => $t->id());
        Schema::dropIfExists('user_role');

        $migrations = dirname(__DIR__, 3).'/database/migrations/';
        (require $migrations.'2023_02_19_000011_add_role_id_to_users_table.php')->up();
        (require $migrations.'2023_02_19_000020_create_users_roles_table.php')->up();

        $this->assertTrue(Schema::hasColumn('admin_users', 'role_id'));
        $this->assertTrue(Schema::hasTable('user_role'));
    }
}

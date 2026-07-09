<?php

namespace KY\AdminPanel\Tests\Unit\DataTypes;

use AdminPanel;
use Illuminate\Http\Request;
use KY\AdminPanel\DataTypes\UserDataType;
use KY\AdminPanel\Tests\TestCase;
use KY\AdminPanel\Tests\Utils\Fixtures\CustomTableUser;

/**
 * @coversDefaultClass \KY\AdminPanel\DataTypes\UserDataType
 */
class UserDataTypeRulesTest extends TestCase
{
    /**
     * @covers ::rules
     */
    public function test_email_unique_rule_targets_default_users_table(): void
    {
        $rules = (new UserDataType)->rules(new Request);

        // Правило unique приводится к строке вида "unique:users,email,...".
        $this->assertStringContainsString('unique:users,email', (string) $rules['email'][2]);
    }

    /**
     * @covers ::rules
     */
    public function test_email_unique_rule_follows_custom_user_model_table(): void
    {
        // Подменяем модель на наследника с таблицей admin_users — валидация должна следовать за ней.
        AdminPanel::useModel('User', CustomTableUser::class);

        $rules = (new UserDataType)->rules(new Request);

        $this->assertStringContainsString('unique:admin_users,email', (string) $rules['email'][2]);
    }
}

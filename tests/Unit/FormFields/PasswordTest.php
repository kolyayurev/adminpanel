<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use KY\AdminPanel\FormFields\Password;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Password
 */
class PasswordTest extends TestCase
{
    /**
     * @covers ::prepareValue
     */
    public function test_prepare_value_hashes_non_empty_password(): void
    {
        $field = new Password;

        $hash = $field->prepareValue('secret', new Request, (object) []);

        $this->assertTrue(Hash::check('secret', $hash));
    }

    /**
     * @covers ::prepareValue
     */
    public function test_prepare_value_returns_existing_password_when_empty_and_without_default(): void
    {
        $field = Password::make('password');
        $model = (object) ['password' => 'existing-hash'];

        $this->assertSame('existing-hash', $field->prepareValue('', new Request, $model));
    }

    /**
     * @covers ::prepareValue
     */
    public function test_prepare_value_hashes_default_password_when_empty_and_default_exists(): void
    {
        $field = Password::make('password')->default('default-secret');
        $model = (object) ['password' => 'existing-hash'];

        $hash = $field->prepareValue('', new Request, $model);

        $this->assertTrue(Hash::check('default-secret', $hash));
    }
}

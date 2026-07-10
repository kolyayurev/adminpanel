<?php

namespace KY\AdminPanel\Tests\Feature;

use KY\AdminPanel\Facades\AdminPanel;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Http\Controllers\AuthController
 */
class AuthControllerTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->group(['prefix' => 'admin'], function () {
            AdminPanel::routes();
        });
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Прод крутится на ru (APP_LOCALE=ru) — проверяем реальные строки, которые увидит пользователь.
        $this->app->setLocale('ru');
    }

    /**
     * @covers ::postLogin
     */
    public function test_post_login_returns_localized_error_for_wrong_password(): void
    {
        $user = $this->createAdminUser(['email' => 'admin@example.com']);

        $response = $this->post(route('adminpanel.postlogin'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email' => 'Неверный email или пароль.']);

        $message = session('errors')->first('email');
        $this->assertNotSame('auth.failed', $message);
    }

    /**
     * @covers ::postLogin
     */
    public function test_post_login_returns_localized_error_after_too_many_attempts(): void
    {
        $user = $this->createAdminUser(['email' => 'admin@example.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('adminpanel.postlogin'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->postJson(route('adminpanel.postlogin'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);

        $message = $response->json('errors.email.0');
        $this->assertNotSame('auth.throttle', $message);
        $this->assertMatchesRegularExpression('/^Слишком много попыток входа\. Повторите через \d+ секунд\.$/u', $message);
    }
}

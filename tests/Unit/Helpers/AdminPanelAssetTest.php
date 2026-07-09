<?php

namespace KY\AdminPanel\Tests\Unit\Helpers;

use KY\AdminPanel\Tests\TestCase;

/**
 * Версионирование ссылок на ассеты админки (кэш-бастинг).
 */
class AdminPanelAssetTest extends TestCase
{
    // Роуты пакета живут в хост-приложении (AdminPanel::routes()), в Testbench их нет —
    // регистрируем именованный adminpanel.assets, чтобы route() в хелпере резолвился.
    protected function defineRoutes($router): void
    {
        $router->get('adminpanel/assets', fn () => '')->name('adminpanel.assets');
    }

    /**
     * @covers \adminpanel_asset
     */
    public function test_adminpanel_asset_keeps_path_query(): void
    {
        $url = adminpanel_asset('css/app.css');

        // Базовое поведение сохранено: путь к файлу едет в параметре path (urlencode).
        $this->assertStringContainsString('path='.urlencode('css/app.css'), $url);
    }

    /**
     * @covers \adminpanel_asset
     */
    public function test_adminpanel_asset_appends_mtime_version_for_existing_file(): void
    {
        $file = dirname(__DIR__, 3).'/public/css/app.css';

        $url = adminpanel_asset('css/app.css');

        // Для реального файла дописывается версия по mtime — при пересборке URL сменится.
        $this->assertStringContainsString('&v='.filemtime($file), $url);
    }

    /**
     * @covers \adminpanel_asset
     */
    public function test_adminpanel_asset_skips_version_for_missing_file(): void
    {
        $url = adminpanel_asset('css/does-not-exist.css');

        // Несуществующий файл — без версии, ссылка остаётся валидной (graceful).
        $this->assertStringNotContainsString('&v=', $url);
    }
}

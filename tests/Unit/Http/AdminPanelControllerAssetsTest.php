<?php

namespace KY\AdminPanel\Tests\Unit\Http;

use Illuminate\Http\Request;
use KY\AdminPanel\Http\Controllers\AdminPanelController;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Http\Controllers\AdminPanelController
 */
class AdminPanelControllerAssetsTest extends TestCase
{
    /**
     * @covers ::assets
     */
    public function test_assets_serves_existing_file_with_immutable_year_cache(): void
    {
        $request = Request::create('/', 'GET', ['path' => 'css/app.css']);

        $response = (new AdminPanelController)->assets($request);

        $this->assertSame(200, $response->getStatusCode());

        $cacheControl = $response->headers->get('Cache-Control');
        // URL версионируется (adminpanel_asset), поэтому кэш — год и immutable.
        $this->assertStringContainsString('immutable', $cacheControl);
        $this->assertStringContainsString('max-age=31536000', $cacheControl);
    }

    /**
     * @covers ::assets
     */
    public function test_assets_returns_404_for_missing_file(): void
    {
        $request = Request::create('/', 'GET', ['path' => 'css/does-not-exist.css']);

        $response = (new AdminPanelController)->assets($request);

        $this->assertSame(404, $response->getStatusCode());
    }
}

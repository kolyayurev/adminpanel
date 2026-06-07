<?php

namespace KY\AdminPanel\Tests\Unit\Models;

use KY\AdminPanel\Models\Seo;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Models\Seo
 */
class SeoTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function test_model_persists_fillable_attributes_and_casts_get_params(): void
    {
        $seo = Seo::factory()->create([
            'url' => '/page',
            'get_params' => ['ref' => 'mail'],
            'title' => 'Title',
            'h1' => 'Heading',
            'seo_text' => 'Text',
            'meta_keywords' => 'one,two',
            'meta_description' => 'Description',
            'meta_og_title' => 'OG Title',
            'meta_og_description' => 'OG Description',
            'status' => 1,
        ]);

        $this->assertInstanceOf(Seo::class, $seo);
        $this->assertSame('/page', $seo->url);
        $this->assertSame(['ref' => 'mail'], $seo->get_params);
        $this->assertSame('Title', $seo->title);
        $this->assertSame('Heading', $seo->h1);
        $this->assertSame('Text', $seo->seo_text);
        $this->assertFalse($seo->usesTimestamps());
    }
}

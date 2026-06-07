<?php

namespace KY\AdminPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use KY\AdminPanel\Database\Factories\SeoFactory;

/**
 * @property int $id
 * @property string $url
 * @property array|null $get_params
 * @property string|null $title
 * @property string|null $h1
 * @property string|null $seo_text
 * @property string|null $meta_keywords
 * @property string|null $meta_description
 * @property string|null $meta_og_title
 * @property string|null $meta_og_description
 * @property int|bool|null $status
 *
 * @method static SeoFactory factory($count = null, $state = [])
 */
class Seo extends Model
{
    use HasFactory;

    protected $table = 'seo';

    public $timestamps = false;

    protected $fillable = [
        'url',
        'get_params',
        'title',
        'h1',
        'seo_text',
        'meta_keywords',
        'meta_description',
        'meta_og_title',
        'meta_og_description',
    ];

    protected $casts = [
        'get_params' => 'array',
    ];
}

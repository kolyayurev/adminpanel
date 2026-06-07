<?php

namespace KY\AdminPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use KY\AdminPanel\Database\Factories\SefFactory;

/**
 * @property int $id
 * @property string $url
 * @property array|null $get_params
 * @property string $alias
 * @property int|bool $status
 *
 * @method static SefFactory factory($count = null, $state = [])
 */
class Sef extends Model
{
    use HasFactory;

    protected $table = 'sef';

    public $timestamps = false;

    protected $fillable = [
        'url',
        'alias',
        'status',
    ];

    protected $casts = [
        'get_params' => 'array',
    ];
}

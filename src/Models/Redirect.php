<?php

namespace KY\AdminPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use KY\AdminPanel\Database\Factories\RedirectFactory;

/**
 * @property int $id
 * @property string $from
 * @property array|null $get_params
 * @property string $to
 * @property int|bool $status
 *
 * @method static RedirectFactory factory($count = null, $state = [])
 */
class Redirect extends Model
{
    use HasFactory;

    protected $table = 'redirects';

    public $timestamps = false;

    protected $fillable = [
        'from',
        'to',
        'status',
    ];

    protected $casts = [
        'get_params' => 'array',
    ];
}

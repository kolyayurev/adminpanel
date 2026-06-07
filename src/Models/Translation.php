<?php

namespace KY\AdminPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use KY\AdminPanel\Database\Factories\TranslationFactory;

/**
 * @property int $id
 * @property string $table_name
 * @property string $column_name
 * @property int $foreign_key
 * @property string $locale
 * @property string|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static TranslationFactory factory($count = null, $state = [])
 */
class Translation extends Model
{
    use HasFactory;

    protected $table = 'translations';

    protected $fillable = ['table_name', 'column_name', 'foreign_key', 'locale', 'value'];
}

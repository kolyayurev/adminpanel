<?php

namespace KY\AdminPanel\Models;

use KY\AdminPanel\Traits\Translatable;
use Str;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use Translatable;

    protected $translatable = ['text','textarea','list','array_builder','ckeditor'];

    protected $table = 'test';
    protected $fillable = [
        'text',
        'textarea',
        'password',
        'ckeditor',
        'coords',
        'file',
        'image',
        'select',
        'color',
        'date',
        'time',
        'timestamp',
        'checkbox'
    ];
}

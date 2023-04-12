<?php

namespace KY\AdminPanel\FormFields;

use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\FilterContract;
use KY\AdminPanel\DataTables\Filters\SelectFilter;

class SwitchField extends Checkbox
{
    protected string $slug = 'switch';
}

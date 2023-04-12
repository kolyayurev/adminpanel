<?php

namespace KY\AdminPanel\Contracts;

use App\AdminPanel\PageTypes\CommonPageType;
use Illuminate\Http\Request;
use KY\AdminPanel\DataTables\Column;

interface FormFieldContract
{
    public function render($dataType = null, $model = null, string $viewType  = 'form');
    public function createContent($dataType, $model ,$type);
    public function getSlug();
    public function prepareValueToSave(Request $request, $model);
    public function afterSave(Request $request, $model);
    public function toColumn() : Column;
}

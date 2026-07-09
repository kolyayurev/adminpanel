<?php

namespace KY\AdminPanel\Http\Controllers;

use Illuminate\Contracts\View\View;
use KY\AdminPanel\Facades\AdminPanel;

class CustomPageController extends Controller
{
    public function index(string $page): View
    {
        $customPage = AdminPanel::getCustomPage($page);

        if (is_null($customPage)) {
            abort(404);
        }

        return view('adminpanel::custom_pages.index', [
            'customPage' => $customPage,
        ]);
    }
}

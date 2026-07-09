<?php

namespace KY\AdminPanel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use KY\AdminPanel\Facades\AdminPanel;

class WidgetController extends Controller
{
    /**
     * Универсальный async-эндпоинт данных виджета — аналог BaseDataController::table.
     * Не привязан к конкретной CustomPage: виджет адресуется собственным слагом, независимо
     * от того, на какой странице (и на скольких страницах) он показан.
     */
    public function data(Request $request, string $widget): JsonResponse
    {
        $widgetInstance = AdminPanel::getWidget($widget);

        if (is_null($widgetInstance)) {
            abort(404);
        }

        return response()->json($widgetInstance->data($request));
    }
}

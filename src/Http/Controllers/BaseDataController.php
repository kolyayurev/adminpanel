<?php

namespace KY\AdminPanel\Http\Controllers;

use AdminPanel;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Traits\Controllers\HandleFormFields;
use KY\AdminPanel\Traits\Controllers\HandleRelation;

class BaseDataController extends Controller
{
    use HandleFormFields, HandleRelation;

    public ?DataTypeContract $dataType;

    public function __construct()
    {
        $request = app('request');
        if ($request->route() !== null) {
            $this->dataType = AdminPanel::getDataType($this->getSlug($request));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $this->authorize('list', $this->dataType->getModel());

        return view($this->dataType->getIndexView(), [
            'dataType' => $this->dataType,
            'isModelTranslatable' => is_translatable($this->dataType->getModel()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $model = $this->dataType->getModel();

        $this->authorize('create', $model);

        return view($this->dataType->getFormView(), [
            'dataType' => $this->dataType,
            'model' => $model,
            'isModelTranslatable' => is_translatable($model)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->dataType->getModel());

        $this->validateModalAware($request);

        $model = $this->storeData($request, $this->dataType);

        return $this->storeReturn($request, $model);
    }

    /**
     * Валидация с оглядкой на модальный режим.
     *
     * Форма модалки уходит обычным multipart-POST'ом, поэтому дефолтный ответ на
     * ValidationException — редирект «назад с ошибками». Фронт повторяет его тем же
     * методом POST на GET-маршрут формы и получает 405, а пользователь — общий тост
     * вместо списка полей. Для `modal=1` отвечаем JSON'ом независимо от Accept.
     */
    protected function validateModalAware(Request $request): void
    {
        $validator = $this->dataType->validator($request);

        if ($request->boolean('modal') && $validator->fails()) {
            abort(response()->json([
                'status' => false,
                'message' => ap_trans('messages.error.validation'),
                'errors' => $validator->errors(),
            ], 422));
        }

        $validator->validate();
    }

    protected function storeReturn(Request $request, $model)
    {
        if ($request->boolean('modal')) {
            return response()->json([
                'status' => true,
                'message' => ap_trans('messages.success.create'),
                'id' => $model->getKey(),
            ]);
        }

        return match ($request->get('submitButton')) {
            'save' => redirect()
                ->route('adminpanel.'.$this->dataType->getSlug().'.edit', $model->id)
                ->with('success', ap_trans('messages.success.create')),
            default =>
//                redirect(Session::get("{$this->dataType->getTable()}.url") ?? route('adminpanel.'.$this->dataType->getSlug().'.index'))
                redirect(route('adminpanel.'.$this->dataType->getSlug().'.index'))
                    ->with('success', ap_trans('messages.success.create')),
        };
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $model = $this->dataType->getModel()->findOrFail($id);

        $this->authorize('update', $model);

        // Eagerload Relations
        $this->eagerLoadRelations($model, is_translatable($model));

        return view($this->dataType->getFormView(), [
            'dataType' => $this->dataType,
            'model' => $model,
            'isModelTranslatable' => is_translatable($model),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $model = $this->dataType->getModel()->findOrFail($id);

        $this->authorize('update', $model);

        $this->validateModalAware($request);

        $model = $this->updateData($request, $this->dataType, $model);

        return $this->updateReturn($request);
    }

    protected function updateReturn(Request $request)
    {
        if ($request->boolean('modal')) {
            return response()->json([
                'status' => true,
                'message' => ap_trans('messages.success.update'),
            ]);
        }

        return match ($request->get('submitButton')) {
            'save' => redirect()
                ->back()
                ->with('success', ap_trans('messages.success.update')),
            default =>
//            redirect(Session::get("{$this->dataType->getTable()}.url") ?? route('adminpanel.'.$this->dataType->getSlug().'.index'))
            redirect(route('adminpanel.'.$this->dataType->getSlug().'.index'))
                ->with('success', ap_trans('messages.success.update')),
        };
    }

    /**
     * Display the specified resource.
     *
     * @return View
     */
    public function show($id)
    {
        $model = $this->dataType->getModel()->findOrFail($id);

        $this->authorize('show', $model);

        $this->eagerLoadRelations($model, is_translatable($model));

        return view($this->dataType->getShowView(), [
            'dataType' => $this->dataType,
            'model' => $model,
            'isModelTranslatable' => is_translatable($model),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $model = $this->dataType->getModel()->findOrFail($id);

        $this->authorize('delete', $model);

        $model->delete();

        // TODO: remove media and other related

        return response()->json(['status' => true, 'message' => ap_trans('messages.success.deleted')], 200);
    }

    /**
     * Форма создания/правки для модалки — тот же вид, но без обвязки layouts.master.
     */
    public function modalForm(Request $request, $id = null): JsonResponse
    {
        if ($id === null) {
            $model = $this->dataType->getModel();
            $this->authorize('create', $model);
        } else {
            $model = $this->dataType->getModel()->findOrFail($id);
            $this->authorize('update', $model);
            $this->eagerLoadRelations($model, is_translatable($model));
        }

        return response()->json([
            'status' => true,
            'template' => view('adminpanel::datatypes.partials.modal-form', [
                'dataType' => $this->dataType,
                'model' => $model,
                'isModelTranslatable' => is_translatable($model),
            ])->render(),
        ]);
    }

    /**
     * Get table.
     */
    public function table(Request $request): JsonResponse
    {
        return $this->dataType->getDataTable($request);
    }

    public function restore(Request $request, $id)
    {
        $model = $this->dataType->getModel()->withTrashed()->findOrFail($id);

        $this->authorize('delete', $model);

        $data = $model->restore($id) ?
            ['success' => ap_trans('messages.success.restored')]
            : ['error' => ap_trans('messages.error.restoring')];

        return redirect()->route('adminpanel.'.$this->dataType->getSlug().'.index')->with($data);
    }

    /**
     * Order items.
     *
     * @return RedirectResponse|Response|Application|Factory|View
     */
    public function order(Request $request)
    {
        // Check permission
        $this->authorize('update', $this->dataType->getModel());

        if (! $this->dataType->showOrderPage()) {
            return redirect()
                ->route('adminpanel.'.$this->dataType->getSlug().'.index')
                ->with([
                    'error' => __('adminpanel::messages.error.ordering_not_set'),
                ]);
        }

        $model = $this->dataType->getModel();
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $model = $model->withTrashed();
        }
        $results = $model->orderBy($this->dataType->getOrderColumn(), $this->dataType->getOrderDirection())->get();

        return view($this->dataType->getOrderView(), [
            'dataType' => $this->dataType,
            'results' => $results,
        ]);
    }

    public function updateOrder(Request $request)
    {
        // Check permission
        $this->authorize('update', $this->dataType->getModel());

        $model = $this->dataType->getModel();

        $order = json_decode($request->input('order'));
        $column = $this->dataType->getOrderColumn();

        foreach ($order as $key => $item) {
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $i = $model->withTrashed()->findOrFail($item->id);
            } else {
                $i = $model->findOrFail($item->id);
            }
            $position = $this->dataType->getOrderDirection() === 'desc' ? (count($order) - $key) : ($key + 1);
            $i->$column = $position;
            $i->save();
        }

        return response()->json(['status' => true], 200);
    }

    public function editField(Request $request)
    {
        $model = $this->dataType->getModel()->findOrFail($request->get('id'));

        $this->authorize('update', $model);

        // Eagerload Relations
        $this->eagerLoadRelations($model, is_translatable($model));

        $field = $this->dataType->getField($request->get('field'));

        return response()->json([
            'status' => true,
            'template' => view('adminpanel::datatables.partials.editable-cell-form', [
                'dataType' => $this->dataType,
                'field' => $field,
                'model' => $model,
            ])->render(),
        ], 200);
    }

    public function updateField(Request $request)
    {
        // TODO:: for multilingual
        $model = $this->dataType->getModel()->findOrFail($request->get('id'));

        $this->authorize('update', $model);

        // TODO: fix
        //        $validator = $this->dataType->validator($request);
        //        $validator->validate();

        $model = $this->updateData($request, $this->dataType, $model);

        return response()->json(['status' => true], 200);
    }

    protected function getSlug(Request $request)
    {
        if (! empty($this->dataType)) {
            $slug = $this->dataType->getSlug();
        } else {
            $slug = explode('.', $request->route()->getName())[1];
        }

        return $slug;
    }
}

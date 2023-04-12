<?php

namespace  KY\AdminPanel\Http\Controllers;

use AdminPanel;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Traits\Controllers\HandleFormFields;
use KY\AdminPanel\Traits\Controllers\HandleRelation;


class BaseDataController extends Controller
{
    use HandleFormFields, HandleRelation;

    public ?DataTypeContract $dataType;


    public function __construct(){
        if(!app()->runningInConsole())
        {
            $request = app('request');
            $this->dataType = AdminPanel::getDataType($this->getSlug($request));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $this->authorize('list',$this->dataType->getModel());

        return view($this->dataType->getIndexView(),[
            'dataType'=>$this->dataType,
            'isModelTranslatable'=>is_translatable($this->dataType->getModel())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $model = $this->dataType->getModel();

        $this->authorize('create',$model);

        return view($this->dataType->getFormView(),[
            'dataType'=>$this->dataType,
            'model'=>$model,
            'isModelTranslatable'=>is_translatable($model)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->authorize('create',$this->dataType->getModel());

        $validator = $this->dataType->validator($request);
        $validator->validate();

        $model = $this->storeData($request, $this->dataType);

        return $this->storeReturn($request,$model);
    }

    protected function storeReturn(Request $request,$model){
        return match ($request->get('submitButton')) {
            'save' => redirect()
                ->route('adminpanel.'.$this->dataType->getSlug().'.edit', $model->id)
                ->with('success', ap_trans('messages.success.create')),
            default =>
//                redirect(Session::get("{$this->dataType->getTable()}.url") ?? route('adminpanel.'.$this->dataType->getSlug().'.index'))
                redirect( route('adminpanel.'.$this->dataType->getSlug().'.index'))
                ->with('success', ap_trans('messages.success.create')),
        };
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return View
     */
    public function edit($id): View
    {
        $model = $this->dataType->getModel()->findOrFail($id);

        $this->authorize('update', $model);

        // Eagerload Relations
        $this->eagerLoadRelations($model,is_translatable($model));

        return view($this->dataType->getFormView(),[
            'dataType'=>$this->dataType,
            'model'=>$model,
            'isModelTranslatable'=>is_translatable($model)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $model = $this->dataType->getModel()->findOrFail($id);

        $this->authorize('update', $model);

        $validator = $this->dataType->validator($request);
        $validator->validate();

        $model = $this->updateData($request, $this->dataType, $model);

        return $this->updateReturn($request);
    }
    protected function updateReturn(Request $request)
    {
        return match ($request->get('submitButton')) {
            'save' => redirect()
                ->back()
                ->with('success',ap_trans('messages.success.update')),
            default =>
//            redirect(Session::get("{$this->dataType->getTable()}.url") ?? route('adminpanel.'.$this->dataType->getSlug().'.index'))
            redirect(route('adminpanel.'.$this->dataType->getSlug().'.index'))
                ->with('success', ap_trans('messages.success.update')),
        };
    }
    /**
     * Display the specified resource.
     *
     * @param $id
     * @return View
     */
    public function show($id)
    {
        $model = $this->dataType->getModel()->findOrFail($id);

        $this->authorize('show',$model);

        $this->eagerLoadRelations($model,is_translatable($model));

        return view($this->dataType->getShowView(),[
            'dataType'=>$this->dataType,
            'model'=>$model,
            'isModelTranslatable'=>is_translatable($model)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $model = $this->dataType->getModel()->findOrFail($id);

        $this->authorize('delete', $model);

        $model->delete();

        //TODO: remove media and other related

        return response()->json(['status'=>true], 200);
    }

    /**
     * Get table.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function table(Request $request): JsonResponse
    {
        return $this->dataType->getDataTable($request);
    }

    public function restore(Request $request, $id)
    {
        $model = $this->dataType->getModel()->withTrashed()->findOrFail($id);

        $this->authorize('delete',$model);

        $data = $model->restore($id) ?
            ['success'  => ap_trans('messages.success.restored'),]
            : ['error'    => ap_trans('messages.error.restoring')];

        return redirect()->route("adminpanel.".$this->dataType->getSlug().".index")->with($data);
    }



    /**
     * Order items.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Auth\Access\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function order(Request $request)
    {
        // Check permission
        $this->authorize('update', $this->dataType->getModel());

        if (!$this->dataType->showOrderPage()) {
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
            'results' => $results
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
            $position = $this->dataType->getOrderDirection() === 'desc' ? (count($order)-$key) :  ($key + 1);
            $i->$column = $position;
            $i->save();
        }

        return response()->json(['status'=>true], 200);
    }


    public function editField(Request $request)
    {
        $model = $this->dataType->getModel()->findOrFail($request->get('id'));

        $this->authorize('update', $model);

        // Eagerload Relations
        $this->eagerLoadRelations($model,is_translatable($model));

        $field = $this->dataType->getField($request->get('field'));

        return  response()->json([
            'status'=>true,
            'template' => view('adminpanel::datatables.partials.editable-cell-form',[
                'dataType'=>$this->dataType,
                'field' => $field,
                'model'=>$model,
            ])->render()
        ], 200) ;
    }

    public function updateField(Request $request)
    {
        //TODO:: for multilingual
        $model = $this->dataType->getModel()->findOrFail($request->get('id'));

        $this->authorize('update', $model);

        //TODO: fix
//        $validator = $this->dataType->validator($request);
//        $validator->validate();

        $model = $this->updateData($request, $this->dataType, $model);

        return response()->json(['status'=>true], 200);
    }

    protected function getSlug(Request $request)
    {
        if (!empty($this->dataType)) {
            $slug = $this->dataType->getSlug();
        } else {
            $slug = explode('.', $request->route()->getName())[1];
        }

        return $slug;
    }



}

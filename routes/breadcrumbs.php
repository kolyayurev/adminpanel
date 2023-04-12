<?php
use Diglactic\Breadcrumbs\Breadcrumbs;

// Dashboard
Breadcrumbs::for('adminpanel.dashboard', function ($trail) {
    $trail->push(ap_trans('breadcrumbs.dashboard'), route('adminpanel.dashboard'));
});

$dataTypes = AdminPanel::getDataTypes();
foreach ($dataTypes as $type)
{
    $slug = $type->getSlug();
    // Dashboard > Index
    Breadcrumbs::for('adminpanel.datatype.'.$slug.'.index', function ($trail,$dataType) use ($slug){
        if(config('adminpanel.breadcrumbs.show_dashboard'))
            $trail->parent('adminpanel.dashboard');

        $trail->push(transForBread($slug,'index'), route('adminpanel.'.$slug.'.index'));
    });

    //  Dashboard > Index > Create
    Breadcrumbs::for('adminpanel.datatype.'.$slug.'.create', function ($trail,$dataType,$model) use ($slug){
        $trail->parent('adminpanel.datatype.'.$slug.'.index',$dataType);
        $trail->push(transForBread($slug,'create'), route('adminpanel.'.$slug.'.create'));
    });

    //  Dashboard > Index > [Id]
    Breadcrumbs::for('adminpanel.datatype.'.$slug.'.show', function ( $trail,$dataType,$model) use ($slug) {
        $trail->parent('adminpanel.datatype.'.$slug.'.index',$dataType);
        $trail->push($model->getKey(), route('adminpanel.'.$slug.'.show', $model->id));
    });

    // Photos > Index > [Id] > Edit
    Breadcrumbs::for('adminpanel.datatype.'.$slug.'.edit', function ( $trail, $dataType, $model) use ($slug) {
        if(auth()->user()->can('show',$model))
            $trail->parent('adminpanel.datatype.'.$slug.'.show', $dataType, $model);
        else
            $trail->parent('adminpanel.datatype.'.$slug.'.index',$dataType);

        $trail->push(transForBread($slug,'edit'), route('adminpanel.'.$slug.'.edit', $model->id));
    });

    //  Dashboard > Index > Order
    Breadcrumbs::for('adminpanel.datatype.'.$slug.'.order', function ($trail,$dataType) use ($slug) {
        $trail->parent('adminpanel.datatype.'.$slug.'.index',$dataType);
        $trail->push(transForBread($slug,'order'), route('adminpanel.'.$slug.'.order'));
    });
}

$pageTypes = AdminPanel::getPageTypes();
foreach ($pageTypes as $type) {

    $slug = $type->getSlug();

    Breadcrumbs::for('adminpanel.setting.' . $slug , function ($trail, $pageType) use ($slug) {
        if(config('adminpanel.breadcrumbs.show_dashboard'))
            $trail->parent('adminpanel.dashboard');

        $trail->push($pageType->getTitle(), route('adminpanel.settings',$slug));
    });
}


Breadcrumbs::for('adminpanel.tools.index', function ($trail){
    $trail->parent('adminpanel.dashboard');
    $trail->push(ap_trans('breadcrumbs.tools.index'), route('adminpanel.tools.index'));
});

Breadcrumbs::for('adminpanel.docs', function ($trail){
    $trail->parent('adminpanel.tools.index');
    $trail->push(ap_trans('breadcrumbs.docs'), route('adminpanel.tools.docs'));
});


Breadcrumbs::for('adminpanel.tools.control-panel', function ($trail){
    $trail->parent('adminpanel.tools.index');
    $trail->push(ap_trans('breadcrumbs.tools.control_panel'), route('adminpanel.tools.control-panel'));
});

Breadcrumbs::for('adminpanel.tools.commands', function ($trail){
    $trail->parent('adminpanel.tools.index');
    $trail->push(ap_trans('breadcrumbs.tools.commands'), route('adminpanel.tools.commands'));
});

Breadcrumbs::for('adminpanel.tools.logs', function ($trail){
    $trail->parent('adminpanel.tools.index');
    $trail->push(ap_trans('breadcrumbs.tools.logs'), route('adminpanel.tools.logs'));
});

Breadcrumbs::for('adminpanel.media', function ($trail){
    $trail->parent('adminpanel.dashboard');
    $trail->push(ap_trans('breadcrumbs.media'), route('adminpanel.media.index'));
});



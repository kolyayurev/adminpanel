<?php

namespace KY\AdminPanel\Http\Controllers;

use AdminPanel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use KY\AdminPanel\DataTypes\SettingDataType;
use KY\AdminPanel\Traits\Controllers\HandleFormFields;
use KY\AdminPanel\Traits\Controllers\HandleRelation;

class SettingController extends Controller
{
    use HandleFormFields,HandleRelation;

    public function __construct(){
        if(!app()->runningInConsole())
        {
            $request = app('request');
            $this->dataType = app(SettingDataType::class);
            $this->pageType = AdminPanel::getPageType($this->getSlug($request));
            if(!empty($this->pageType))
                $this->dataType->setFields($this->pageType->fields());
        }
    }

    /**
     * Show the form for editing items of the specified resource.
     *
     * @param $name
     * @return View
     */
    public function index($slug)
    {
        $pageType = AdminPanel::getPageType($slug);

        if(is_null($pageType))
            abort(404);

        // TODO refactor.
        foreach ($pageType->getFieldsName() as $name){
            AdminPanel::model('Setting')->firstOrCreate(['key'=>$name]);
        }

        $settings = AdminPanel::model('Setting')->whereIn('key',$pageType->getFieldsName())->get();

        $isModelTranslatable = is_translatable(AdminPanel::model('Setting'));
        $this->eagerLoadRelations($settings, $isModelTranslatable);

        foreach ($settings as $setting)
        {
            $setting->{$setting->key} = $setting->value;
            if($isModelTranslatable && $setting->translations && $setting->translations->count()){
                $translation = $setting->translations->where('column_name','value')->first();
                $translation->column_name = $setting->key;
                $setting->translations = $translation;
            }
        }

        return view($pageType->getView(), [
            'pageType' => $pageType,
            'fields' => $pageType->getFields(),
            'dataType' => $this->dataType,
            'settings'=> $settings,
            'isModelTranslatable'=>is_translatable(AdminPanel::model('Setting'))
        ]);
    }

    /**
     * Update the specified items of resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function update(Request $request)
    {
        $pageType = AdminPanel::getPageType($request->slug);

//        $this->dataType->setFields($pageType->fields());

        $model = AdminPanel::model('Setting');

        foreach ($pageType->getFields() as $field){
            $model = AdminPanel::model('Setting')->firstOrCreate(['key'=>$field->get('name')]);

            $translation = is_translatable($model)
                ? $model->prepareSettingTranslation($request,$field->get('name'))
                : [];

            $field->beforeSave($request,$model);

            $value = $field->prepareValueToSave($request,$model);
            $model->display_name = $field->get('label');
            $model->value = $value;
            $model->save();

            if (!empty($translation)) {
                $model->saveTranslations([$translation]);
            }
            $field->afterSave($request,$model);
        }

        return redirect()->back()->withSuccess('Контент успешно сохранен');
    }

    protected function getSlug(Request $request)
    {
        if (!empty($this->pageType)) {
            $slug = $this->pageType->getSlug();
        } else {
            $slug = $request->route('name');
        }

        return $slug;
    }

}

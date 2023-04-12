<?php

namespace KY\AdminPanel\Traits\Controllers;

use AdminPanel,DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait HandleFormFields
{
    public function storeData(Request $request, $dataType)
    {
        $fields = $this->dataType->getFieldsForStore();
//        $this->removeHiddenField($fields,$this->dataType->getModel());

        return $this->storeUpdateData($request,$dataType,$fields,$this->dataType->getModel());
    }

    public function updateData(Request $request, $dataType,$model)
    {
        $fields = $this->dataType->getFieldsForUpdate();
//        $this->removeHiddenField($fields,$this->dataType->getModel());

        return $this->storeUpdateData($request,$dataType,$fields,$model);
    }

    protected function storeUpdateData(Request $request, $dataType, $fields,$model)
    {

        DB::beginTransaction();
        /*
         * Prepare Translations and Transform data
         */
        $translations = is_translatable($model)
            ? $model->prepareTranslations($request)
            : [];

        // Remove id field
        $fields = $fields->filter(function ($field) use ($model){
            return $field->get('name') !== $model->getKeyName();
        });

        foreach ($fields as $field) {
            $field->beforeSave($request,$model);
        }
        foreach ($fields as $field) {

            if(!$field->needSave() || !$request->has($field->get('name')))
                continue;

            $model->{$field->get('name')} = $field->prepareValueToSave($request,$model);
        }

        $model->save();

        foreach ($fields as $field) {
            $field->afterSave($request,$model);
        }

        // Save translations
        if (count($translations) > 0) {
            $model->saveTranslations($translations);
        }

        DB::commit();

        return $model;
    }


    /**
     *
     * Remove fields for datatype, hat is not specified in `only` or `except` options
     *
     */
    protected function removeHiddenField($fields,$model)
    {
        //TODO: add access functions to BaseFormField
        foreach ($fields as $key => $field) {
            if (@$field->hasOnly()) {
                if(!in_array($model->getKey(),$field->getOnly()) )
                    $fields->forget($key);
            }
            if (@$field->hasExcept()) {
                if(in_array($model->getKey(),$field->getExcept()))
                    $fields->forget($key);
            }
            if (@$field->hasRoles()) {
                if (@$field->getOnlyRoles()) {
                    if(!auth()->user()->hasRole($field->getOnlyRoles()))
                        $fields->forget($key);
                }
                if (@$field->getExceptRoles()) {
                    if(auth()->user()->hasRole($field->getExceptRoles()))
                        $fields->forget($key);
                }
            }
        }
        // Reindex collection
        $fields = $fields->values();
    }

    /**
     * Eagerload relationships.
     *
     * @param mixed      $data Modal or Collections
     * @param bool       $isModelTranslatable
     *
     * @return void
     */
    protected function eagerLoadRelations($data,bool $isModelTranslatable = false)
    {
        // Eagerload Translations
        if (config('adminpanel.multilingual.enabled')) {
            // Check if it is Translatable
            if ($isModelTranslatable) {
                $data->load('translations');
            }
        }
    }

}

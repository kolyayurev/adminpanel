<?php

namespace KY\AdminPanel\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;

interface DataTypeContract
{
    public function getName():string;
    public function getTitle():string;
    public function getSlug():string;
    public function getModel();
    public function getPolicy():string;
    public function getController():string;

    public function getIndexView():string;
    public function getFormView():string;
    public function getShowView():string;

    public function validator(Request $request):Validator;
    public function rules(Request $request):array;
    public function messages():array;
    public function customAttributes():array;

    public function layout(): Collection;
    public function fields():Collection;
    public function getFormFields(string $type):Collection;
    public function getColumns():Collection;

    public function getDataTable(Request $request): JsonResponse;

}

<?php


if (!function_exists('docs_path')) {
    function docs_path()
    {
        return dirname(__DIR__, 2).'/docs/';
    }
}

if (!function_exists('setting')) {
    function setting($key, $default = null, $locale = null)
    {
        return KY\AdminPanel\Facades\AdminPanel::setting($key, $default,$locale);
    }
}

if (!function_exists('adminpanel_asset')) {
    function adminpanel_asset($path, $secure = null)
    {
        return route('adminpanel.assets').'?path='.urlencode($path);
    }
}

//if (!function_exists('menu')) {
//    function menu($menuName,$type = null)
//    {
//        return KY\AdminPanel\Facades\AdminPanel::getMenu($menuName)->display($type);
//    }
//}

if (!function_exists('name2id')) {
    function name2id($name)
    {
        return Str::snake(str_replace(['[',']'], ['_',''], $name));
    }
}

if (!function_exists('array2object')) {

    function array2object($array)
    {
        $object = $array;

        if(is_array($array))
            $object = json_decode(json_encode($array), FALSE);

        return $object;
    }
}

if (!function_exists('string2array_of_objects')) {
    function string2array_of_objects($string)
    {
        $object = $string;

        if(is_string($string))
            $object = json_decode($string, FALSE);

        return $object;
    }
}

if (!function_exists('string2array')) {
    function string2array($string)
    {
        $object = $string;

        if(is_string($string))
            $object = json_decode($string, TRUE);

        return $object;
    }
}


if (!function_exists('printString')) {

    function printString($string,$default="")
    {
        if($string==null || !$string)
            $string=$default;
        return json_encode($string);
    }
}

if (!function_exists('printArray')) {

    function printArray($array,$default=[])
    {
        if($array==null || !$array)
            $array=$default;
        return is_array($array)?json_encode($array):$array;
    }
}

if (!function_exists('printObject')) {

    function printObject($obj)
    {
        if($obj==null || !$obj)
            $obj=new stdClass();

        return is_string($obj)? $obj :json_encode($obj);
    }
}
if (!function_exists('printInt')) {

    function printInt($number,$default=null)
    {
        $number = (int)$number;
        if($number==null || !$number)
            $number=$default;
        return json_encode($number);
    }
}
if (!function_exists('printFloat')) {

    function printFloat($number,$default=null)
    {
        $number = (float)$number;
        if($number==null || !$number)
            $number=$default;
        return json_encode($number);
    }
}
if (!function_exists('printBool')) {

    function printBool($bool)
    {
        return json_encode($bool ? true : false);
    }
}

if (!function_exists('get_file_name')) {
    function get_file_name($name)
    {
        preg_match('/(_)([0-9])+$/', $name, $matches);
        if (count($matches) == 3) {
            return Illuminate\Support\Str::replaceLast($matches[0], '', $name).'_'.(intval($matches[2]) + 1);
        } else {
            return $name.'_1';
        }
    }
}


if (!function_exists('fields_in_block')) {
    function fields_in_block(\Illuminate\Support\Collection $fields,\KY\AdminPanel\Contracts\BlockContract $block) : bool
    {
        if(!$block->isVisibleOnlyWhenHasFields())
            return true;

        $fieldsName = $fields->map(function ($f){
            return $f->get('name');
        })->toArray();
        $blockFieldsName = $block->getFieldsName();

        foreach ($fieldsName as $fieldName){
            if($blockFieldsName->has($fieldsName))
            {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('vue_instance_name')) {
    function vue_instance_name($field, $model) : string
    {
        return 'vue_'.$field->get('name').(is_field_translatable($model, $field)?'_i18n ':'');
    }
}



<?php

if (!function_exists('ap_trans')) {
    function ap_trans($key) :string
    {
        return trans('adminpanel::'.$key);
    }
}

if (!function_exists('transForBread')) {
    function transForBread($type,$route) :string
    {
        $commonKey = 'adminpanel::breadcrumbs.common.'.$route;
        $key = 'adminpanel::breadcrumbs.'.$type.'.'.$route;
        return trans(\Lang::has($key) ? $key: $commonKey);
    }
}

if (!function_exists('transForDataTypeTitle')) {
    function transForDataTypeTitle($type,$title,array $data = []) :string
    {
        $commonKey = 'adminpanel::datatypes.common_titles.'.$title;
        $key = 'adminpanel::datatypes.titles.'.$type.'.'.$title;
        return trans(\Lang::has($key) ? $key: $commonKey,$data);
    }
}



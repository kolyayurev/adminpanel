@php($params = json_decode($field->getValue($model),'true'))
@foreach ($params ?? [] as $param)
    <code>{{ $param['name'] }}={{ $param['value'] }}</code><br>
@endforeach

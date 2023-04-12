@php
    if (is_array($field->getValue($model))) {
        $files = $field->getValue($model);
    } else {
        $files = json_decode($field->getValue($model));
    }
@endphp
<div class="form-group mb-2 border-bottom">
    <h4>{{ $field->get('label') }}</h4>
@if ($files)
    @if ($field->get('showAsImage'))
        @foreach (array_slice($files, 0, 3) as $file)
        <img src="@if( !filter_var($file, FILTER_VALIDATE_URL)){{ APMedia::getUrl( $file ) }}@else{{ $file }}@endif" style="width:50px">
        @endforeach
    @else
        <ul>
        @foreach (array_slice($files, 0, 3) as $file)
            <li>{{ $file }}</li>
        @endforeach
        </ul>
    @endif
    @if (count($files) > 3)
        {{ __('adminpanel::media.files_more', ['count' => (count($files) - 3)]) }}
    @endif
@elseif (is_array($files) && count($files) == 0)
    {{ trans('adminpanel::media.files_none') }}
@elseif ($field->getValue($model) != '')
    @if ($field->get('showAsImage'))
        <img src="@if( !filter_var($field->getValue($model), FILTER_VALIDATE_URL)){{ APMedia::getUrl( $field->getValue($model) ) }}@else{{ $field->getValue($model) }}@endif" style="width:50px">
    @else
        {{ $field->getValue($model) }}
    @endif
@else
    {{ trans('adminpanel::media.files_none') }}
@endif
</div>

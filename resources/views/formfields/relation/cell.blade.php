@if($field->get('relatedModel') && $field->get('type'))

    @if(class_exists($field->get('relatedModel')))

        @php $relationshipField = $field->get('name'); @endphp

        @if($field->isBelongsTo())
            @php
                $relationshipData = (isset($data)) ? $data : $model;
                $relationModel = app($field->get('relatedModel'));
                $query = $relationModel::where($field->get('key'),$relationshipData->{$field->get('column')})->first();
            @endphp

            @if(isset($query))
                <p><a class="" href="{{ route('adminpanel.'.$dataType->getSlug().'.edit',$query->getKey()) }}" target="_blank">{{ $query->{$field->get('displayedField')} }}</a></p>
            @else
                <p>{{ __('adminpanel::common.no_results') }}</p>
            @endif
        @elseif($field->isHasOne())

            @php
                $relationshipData = (isset($data)) ? $data : $model;

                $relationModel = app($field->get('relatedModel'));
                $query = $relationModel::where($field->get('column'), '=', $relationshipData->{$field->get('key')})->first();

            @endphp

            @if(isset($query))
                <p>{{ $query->{$field->get('displayedField')} }}</p>
            @else
                <p>{{ __('adminpanel::common.no_results') }}</p>
            @endif

        @elseif($field->isHasMany())
            @php
                $relationshipData = (isset($data)) ? $data : $model;
                $relationModel = app($field->get('relatedModel'));

                $selected_values = $relationModel::where($field->get('column'), '=', $relationshipData->{$field->get('key')})->get()->map(function ($item, $key) use ($field) {
                    return $item->{$field->get('displayedField')};
                })->all();
            @endphp

            @php
                $string_values = implode(", ", $selected_values);
                if(mb_strlen($string_values) > 25){ $string_values = mb_substr($string_values, 0, 25) . '...'; }
            @endphp
            @if(empty($selected_values))
                <p>{{ __('adminpanel::common.no_results') }}</p>
            @else
                <p>{{ $string_values }}</p>
            @endif

        @elseif($field->isBelongsToMany())

            @php
                $relationshipData = (isset($data)) ? $data : $model;

                $selected_values = isset($relationshipData) ? $relationshipData->belongsToMany($field->get('relatedModel'), $field->get('pivotTable'), $field->get('foreign_pivot_key'), $field->get('related_pivot_key') , $field->get('parent_key'), $field->get('key'))->get()->map(function ($item, $key) use ($field) {
                    return $item->{$field->get('displayedField')};
                })->all() : array();
            @endphp

            @php
                $string_values = implode(", ", $selected_values);
                if(mb_strlen($string_values) > 25){ $string_values = mb_substr($string_values, 0, 25) . '...'; }
            @endphp
            @if(empty($selected_values))
                <p>{{ __('adminpanel::common.no_results') }}</p>
            @else
                <p>{{ $string_values }}</p>
            @endif

        @endif
    @else
        cannot make relationship because {{ $field->get('relatedModel') }} does not exist.
    @endif

@endif

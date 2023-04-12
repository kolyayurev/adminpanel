@if($field->get('relatedModel') && $field->get('type'))
<div class="form-group mb-2 border-bottom">
    <h4>{{ $field->get('label') }}</h4>
    @include('adminpanel::multilingual.input-hidden-show')
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
                <p><a class="" href="{{ route('adminpanel.'.$dataType->getSlug().'.edit',$query->getKey()) }}" target="_blank">{{ $query->{$field->get('displayedField')} }}</a></p>
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
                $relationModel = app($field->get('relatedModel'));
                $query = $relationModel::where($field->get('column'), '=', $model->{$field->get('key')})->get();
            @endphp

            @if(isset($query) && $query->count())
                <ul>
                    @foreach($query as $query_res)
                        <li><a href="{{ route('adminpanel.'.$dataType->getSlug().'.show',$query_res->{$field->get('key')}) }}" target="_blank"> {{ $query_res->{$field->get('displayedField')} }}</a></li>
                    @endforeach
                </ul>

            @else
                <p>{{ __('adminpanel::common.no_results') }}</p>
            @endif

        @elseif($field->isBelongsToMany())

            @php
                $relationshipData = (isset($data)) ? $data : $model;

                $selected_values = isset($relationshipData) ? $relationshipData->belongsToMany($field->get('relatedModel'), $field->get('pivotTable'), $field->get('foreign_pivot_key'), $field->get('related_pivot_key'), $field->get('parent_key'), $field->get('key'))->get()->map(function ($item, $key) use ($field) {
                    return $item->{$field->get('displayedField')};
                })->all() : array();
            @endphp

            @if(empty($selected_values))
                <p>{{ __('adminpanel::common.no_results') }}</p>
            @else
                <ul>
                    @foreach($selected_values as $selected_value)
                        <li>{{ $selected_value }}</li>
                    @endforeach
                </ul>
            @endif
        @endif
    @else
        cannot make relationship because {{ $field->get('relatedModel') }} does not exist.
    @endif
</div>
@endif

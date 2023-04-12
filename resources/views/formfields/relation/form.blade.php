@if($field->get('relatedModel') && $field->get('type'))
    <div class="mb-3">
        @if(class_exists($field->get('relatedModel')))

            @if($field->isBelongsTo())

                @php
                    $relationModel = app($field->get('relatedModel'));
                    $query = $relationModel::where($field->get('key'),$model->{$field->get('column')})->first();
                @endphp
                @if($field->get('disabled'))
                    @php
                        $value =  $query?($query->getKey()??null):null;
                        $label =  $query->{$field->get('displayedField')}??null;
                    @endphp
                    @if($value)
                        <a href="{{ route('adminpanel.'.$field->get('table').'.edit',$query->getKey()) }}"
                           target="_blank">@lang('adminpanel::common.buttons.show')</a>
                    @endif

                    <x-adminpanel::forms.input type="hidden" name="{{ $field->get('column') }}" value="{{ $value }}"/>
                    <x-adminpanel::forms.input disabled value="{{ $label }}"/>
                @else
                    @php
                        $query = $relationModel::where($field->get('key'), old($field->get('column'), $model->{$field->get('column')}))->get();
                    @endphp
                    <x-adminpanel::forms.select
                        mode="ajax"
                        label="{{ $field->get('label') }}"
                        name="{{ $field->get('column') }}"
                        :instruction="$field->get('instruction')"
                        :required="$field->get('required')"
                        :disabled="$field->get('disabled')"
                        data-component="relation"
                        data-datatype="{{ $dataType->getSlug() }}"
{{--                        TODO: refactor--}}
                        data-pagetype="{{ $field->get('pageTypeSlug') }}"
                        data-field="{{ $field->get('name') }}"
                        :data-id="!is_null($model->getKey())?$model->getKey():''"
                        data-method="{{ !is_null($model->getKey()) ? 'update' : 'create' }}"
                    >
                        @if(!$field->get('required'))
                            <option value="">{{__('adminpanel::common.none')}}</option>
                        @endif

                        @foreach($query as $relationshipData)
                            <option value="{{ $relationshipData->{$field->get('key')} }}"
                                    @selected(old($field->get('column'), $model->{$field->get('column')}) == $relationshipData->{$field->get('key')})>{{ $relationshipData->{$field->get('displayedField')} }}</option>
                        @endforeach
                    </x-adminpanel::forms.select>
                @endif

            @elseif($field->isHasOne())

                @php
                    $relationshipData = (isset($data)) ? $data : $model;

                    $relationModel = app($field->get('relatedModel'));
                    $query = $relationModel::where($field->get('column'), '=', $relationshipData->{$field->get('key')})->first();

                @endphp
                <div class="form-group">
                    <label>{{ $field->get('label') }}</label>
                    @if(isset($query))
                        <p><a class="" href="{{ route('adminpanel.'.$dataType->getSlug().'.edit',$query->getKey()) }}" target="_blank">{{ $query->{$field->get('displayedField')} }}</a></p>
                    @else
                        <p>{{ __('adminpanel::common.no_results') }}</p>
                    @endif
                </div>

            @elseif($field->isHasMany())

                @php
                    $relationModel = app($field->get('relatedModel'));
                    $query = $relationModel::where($field->get('column'), '=', $model->{$field->get('key')})->get();
                @endphp
                <div class="form-group">
                    <label>{{ $field->get('label') }}</label>
                    @if(isset($query) && $query->count())
                        <ul>
                            @foreach($query as $query_res)
                                <li>
                                    <a href="{{ route('adminpanel.'.$field->get('table').'.show',$query_res->{$field->get('key')}) }}"
                                       target="_blank"> {{ $query_res->{$field->get('displayedField')} }}</a></li>
                            @endforeach
                        </ul>
                    @else
                        <p>{{ __('adminpanel::common.no_results') }}</p>
                    @endif
                </div>

            @elseif($field->isBelongsToMany())

                @php
                    $selected_values = isset($model) ? $model->belongsToMany($field->get('relatedModel'), $field->get('pivotTable'), $field->get('foreignPivotKey'), $field->get('relatedPivotKey'), $field->get('parentKey'), $field->get('key'))->get()->map(function ($item, $key) use ($field) {
                        return $item->{$field->get('key')};
                    })->all() : array();
                    $relationshipOptions = app($field->get('relatedModel'))->all();
                    $selected_values = old($field->get('name'), $selected_values);
                    $relationshipOptions = $relationshipOptions->filter(function ($option, $key) use ($field,$selected_values) {
                        return in_array($option->{$field->get('key')}, $selected_values);
                    });
                @endphp
                @if($field->get('readonly'))
                    <div class="form-group">
                        <label>{{ $field->get('label') }}</label>
                        @if ($relationshipOptions->count())
                            <ul style="padding-left: 0">
                                @foreach ($relationshipOptions as $relationshipOption)
                                    <li class="form-control" style="margin-bottom: 5px">
                                        <input type="hidden" name="{{ $field->get('name') }}[]"
                                               value="{!! printInt($relationshipOption->{$field->get('key')}) !!}">
                                        <a href="{{ route('adminpanel.'.$field->get('table').'.show',$relationshipOption->{$field->get('key')}) }}"
                                           target="_blank"> {{ $relationshipOption->{$field->get('displayedField')} }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="form-control">{{ __('adminpanel::common.no_results') }}</p>
                        @endif
                    </div>
                @else
                    <x-adminpanel::forms.select
                        mode="{{ $field->get('taggable') ? 'taggable' : 'ajax' }}"
                        label="{{ $field->get('label') }}"
                        name="{{ $field->get('name') }}[]"
                        multiple
                        :instruction="$field->get('instruction')"
                        :required="$field->get('required')"
                        :disabled="$field->get('disabled')"
                        data-component="relation"
                        data-datatype="{{ $dataType->getSlug() }}"
                        data-field="{{$field->get('name')}}"
                        :data-id="!is_null($model->getKey())?$model->getKey():''"
                        data-method="{{ !is_null($model->getKey()) ? 'update' : 'create' }}"
{{--                    TODO: remake table to datatype --}}
                        :data-route="$field->get('taggable')?route('adminpanel.'.$field->get('table').'.store'):''"
                        :data-label="$field->get('taggable')?$field->get('displayedField'):''"
                        :data-error-message="$field->get('taggable')?ap_trans('content-type.error_tagging'):''"
                    >
                        @if(!$field->get('required'))
                            <option value="">{{__('adminpanel::common.none')}}</option>
                        @endif

                        @foreach($relationshipOptions as $relationshipOption)
                            <option value="{{ $relationshipOption->{$field->get('key')} }}"
                                    @selected(in_array($relationshipOption->{$field->get('key')}, $selected_values))>{{ $relationshipOption->{$field->get('displayedField')} }}</option>
                        @endforeach
                    </x-adminpanel::forms.select>
                @endif
            @endif
        @else
            cannot make relationship because {{ $field->get('relatedModel') }} does not exist.
        @endif
    </div>
@endif

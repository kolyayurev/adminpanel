@php($vue_instance_name = vue_instance_name($field, $model))

<div class="mb-3">
    <div class="form-group coordinates-field" id="{{ $field->getId() }}" v-cloak>
        @if (!empty($field->get('label')))
            <label for="{{ name2id($field->get('name')) }}">{{ $field->get('label')  }}</label>
        @endif
        <input type="hidden" name="{{ $field->get('name') }}"
               class="form-control is-vue"
               :value="printObject(value)"
               data-vue-instance="{{ $vue_instance_name }}"
        />
        <div  @class(['is-invalid'=>true || $errors->has($field->get('name'))])>
            <v-geo-picker v-model="value"
                          placeholder="{{ $field->getPlaceholder()  }}"
                          title="{{ $field->getPlaceholder()  }}"
                          api-key="{{ config('adminpanel.ymaps.key') }}"
                          :center="[{{config('adminpanel.ymaps.center.lat')}},{{config('adminpanel.ymaps.center.lng')}}]"
                          :zoom="{{config('adminpanel.ymaps.zoom')}}"
            ></v-geo-picker>
        </div>
        @error($field->get('name'))
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
        @if (!empty($field->get('instruction')))
            <x-adminpanel::instruction :text="$field->get('instruction')"></x-adminpanel::instruction>
        @endif
    </div>
</div>


@push('vue')
    <script>
        var {{$vue_instance_name}} = createVueApp({ // important
            data() {
                return {
                    value: {!! printObject(old($field->get('name'),$field->getValue($model))) !!},
                }
            },
            created() {
                @if (is_field_translatable($model, $field))
                    this.updateLocaleData(this.value)  // important
                @endif
            },
            mounted() {
                vueFieldInstances['{{$vue_instance_name}}'] = this  // important
            },
            methods: {

                @if (is_field_translatable($model, $field))
                updateLocaleData(value) {
                    this.value = this.isJsonValid(value) ? JSON.parse(value) : (value ? value : {})
                }
                @endif
            }
        }).mount('#{{ $field->getId() }}');
    </script>
@endpush

@php($vue_instance_name = vue_instance_name($field, $model))

<div class="mb-3" id="{{ $field->getId() }}"  v-cloak>
    <div class="form-group media-picker-field">
        <label>{{ $field->get('label') }}</label>
        @include('adminpanel::multilingual.input-hidden-form')
        <input type="hidden" name="{{$field->get('name')}}"
               class="form-control is-vue"
               :value="this.options.allowMultiSelect?printObject(data):data"
               data-vue-instance="{{ $vue_instance_name }}"
        />
        <br>
        <small class="text-muted ml-1"><em>{!! $field->getAfterLabel() !!}</em></small>
        <div  class="media-picker-field__content @if($errors->has($field->get('name'))) is-invalid @endif">
            <div class="media-picker-field__form">
                <div class="media-picker-field__form-input" >
                    @{{ !options.allowMultiSelect && getFiles().length?getFileName(data):lang.get('media.choose_file') }}
                </div>
                <el-button class="media-picker-field__form-button" type="primary"
                           @click="openMediaPicker">@{{ lang.get('common.'+(getFiles().length?'edit':'choose')) }}
                </el-button>
            </div>
            <div class="media-picker-field__wrap" v-if="getFiles().length">
                <el-scrollbar>
                    <div class="media-picker-field__items">
                        <template v-for="(file,idx) in getFiles()">
                            <v-files-item :file="file"></v-files-item>
                        </template>
                    </div>
                </el-scrollbar>
            </div>

            <v-dialog-media-picker v-model="data" ref="dialog"></v-dialog-media-picker>
        </div>
        @error($field->get('name'))
{{--        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>--}}
        @enderror
    </div>
</div>
@push('vue')
    <script>
        var {{ $vue_instance_name }} = createVueApp({
            data() {
                return {
                    options: {!! printArray($field->getOptions()) !!},
                    data: {!! is_array($data)? printArray($data) : printString($data) !!},
                }
            },
            created() {
                @if (is_field_translatable($model, $field))
                    this.updateLocaleData(this.data)  // important
                @endif
            },
            mounted() {
                vueFieldInstances['{{$vue_instance_name}}'] = this  // important
            },
            methods: {
                openMediaPicker() {
                    this.$refs.dialog.init(this.options);
                },

                getFiles: function () {
                    if (!this.options.allowMultiSelect) {
                        let data = [];

                        if (this.data !== '') {
                            data.push(this.data);
                        }

                        return data;
                    } else {
                        return JSON.parse(JSON.stringify(this.data));
                    }
                },

                @if (is_field_translatable($model, $field))
                updateLocaleData(data) {
                    // console.log('updateLocaleData')
                    let dataTemp = this.options.allowMultiSelect?[]:'';
                    if(this.isJsonValid(data))
                    {
                        dataTemp = JSON.parse(data);
                        if(!this.options.allowMultiSelect && (dataTemp instanceof Array) && dataTemp.length)
                            dataTemp = dataTemp[0];
                    }
                    else
                    {
                        if(this.options.allowMultiSelect && !(data instanceof Array) && data.length)
                            dataTemp.push(data)
                        else
                            dataTemp = data
                    }
                    this.data =  dataTemp;
                }
                @endif
            }
        }).mount('#{{ $field->getId()  }}');
    </script>
@endpush

@php
    $managerOptions = $field->get('managerOptions');
@endphp

@php($vue_instance_name = vue_instance_name($field, $model))

<div class="mb-3">
    <div class="form-group gallery-field" id="{{ $field->getId() }}" v-cloak>
        <label for="{{ name2id($field->get('name')) }}">{{ $field->get('label') }}</label>
        @include('adminpanel::multilingual.input-hidden-form')
        <input type="hidden" name="{{$field->get('name')}}"
               class="form-control is-vue"
               :value="printObject(items)"
               data-vue-instance="{{ $vue_instance_name }}"
        />
        <el-button
            class="array-builder-field__add-button"
            v-if="items.length < maxItems"
            @click="addItem"
            type="primary"
            size="small">
            <i class="bi bi-plus-lg"></i>&nbsp;
            @{{ lang.get('common.add') }}
        </el-button>
        <draggable v-if="items.length" v-model="items" item-key="title" class="gallery-field__items">
            <template #item="{element,index}">
                <el-card class="gallery-field__item">
                    <div class="image-box">
                        <v-media-icon :file="element[previewMediaPickerOptions.name]"/>
                        <img v-if="element[previewMediaPickerOptions.name]" :src="storagePath(element[previewMediaPickerOptions.name])" class="image">
                    </div>
                    <div class="mt-3">
                        <span>@{{ functions.displayValue(element) }}</span>
                        <div class="bottom ">
                            <el-button class="button" type="primary" @click="editItem(index)" size="small"><i
                                    class="bi bi-pencil-square"></i></el-button>
                            <el-button class="button" type="danger" @dblclick="deleteItem(index)" size="small"><i
                                    class="bi bi-trash3"></i></el-button>
                        </div>
                    </div>
                </el-card>
            </template>
        </draggable>
        <div v-else class="list-item-empty"></div>
        <el-dialog
            class="dialog --form"
            v-model="formDialogVisibility"
            :title="lang.get('common.'+(isEdit?'edit':'add'))"
            top="10vh"
            @closed="resetValidation()"
        >
            <el-form
                :model="model"
                :rules="rules"
                ref="vueForm"
                label-position="top">
                <el-form-item :label="videoMediaPickerOptions.label ?? 'Видео'" :prop="videoMediaPickerOptions.name">
                    <div id="{{ $field->getMediaPicker()->getId() }}" class="media-picker-field">
                        <div class="media-picker-field__form">
                            <div class="media-picker-field__form-input" >
                                @{{ model[videoMediaPickerOptions.name].length?getFileName(model[videoMediaPickerOptions.name]):lang.get('media.choose_file') }}
                            </div>
                            <el-button class="media-picker-field__form-button" type="primary"
                                       @click="openVideoMediaPicker">@{{ lang.get('common.'+(model[videoMediaPickerOptions.name]?'edit':'choose')) }}
                            </el-button>
                        </div>
                        <div class="media-picker-field__wrap">
                            <div class="media-picker-field__single" v-if="model[videoMediaPickerOptions.name]">
                                <v-media-icon class="media-picker-field__single-img" :file="model[videoMediaPickerOptions.name]"> </v-media-icon>
                            </div>
                        </div>
                        <input type="hidden" v-model="model[videoMediaPickerOptions.name]"/>
                        <input type="hidden" :name="videoMediaPickerOptions.name" />
                        <v-dialog-media-picker close-after-selection v-model="model[videoMediaPickerOptions.name]" ref="videoDialog"></v-dialog-media-picker>
                    </div>
                </el-form-item>
                <el-form-item :label="previewMediaPickerOptions.label ?? 'Превью'" :prop="previewMediaPickerOptions.name">
                    <div id="{{ $field->getMediaPicker()->getId() }}" class="media-picker-field">
                        <div class="media-picker-field__form">
                            <div class="media-picker-field__form-input" >
                                @{{ model[previewMediaPickerOptions.name].length?getFileName(model[previewMediaPickerOptions.name]):lang.get('media.choose_file') }}
                            </div>
                            <el-button class="media-picker-field__form-button" type="primary"
                                       @click="openPreviewMediaPicker">@{{ lang.get('common.'+(model[previewMediaPickerOptions.name]?'edit':'choose')) }}
                            </el-button>
                        </div>
                        <div class="media-picker-field__wrap">
                            <div class="media-picker-field__single" v-if="model[previewMediaPickerOptions.name]">
                                <v-media-icon class="media-picker-field__single-img" :file="model[previewMediaPickerOptions.name]"> </v-media-icon>
                            </div>
                        </div>
                        <input type="hidden" v-model="model[previewMediaPickerOptions.name]"/>
                        <input type="hidden" :name="previewMediaPickerOptions.name" />
                        <v-dialog-media-picker close-after-selection v-model="model[previewMediaPickerOptions.name]" ref="previewDialog"></v-dialog-media-picker>
                    </div>
                </el-form-item>
                <el-row>
                    <el-col v-for="field in fields" :md="field.col" :key="field.name">
                        <el-form-item :label="field.label" :prop="field.name">
                            <component :is="field.component"
                                       v-bind="field.props"
                                       v-model="model[field.name]"
                            >
                            </component>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
            <template #footer>
                <el-button @click="saveItem" type="primary"><i :class="'bi bi-'+(isEdit?'check':'plus')"></i>@{{ lang.get('common.'+(isEdit?'save':'add')) }}</el-button>
            </template>
        </el-dialog>

    </div>
</div>



@push('vue')
    <script>
        var {{$vue_instance_name}} = createVueApp({ // important
            data() {
                return {
                    formDialogVisibility: false,
                    minItems: {{ printInt($field->get('min')) }},
                    maxItems: {{ printInt($field->get('max')) }},
                    fields: {!! printObject($field->getFields()->toArray()) !!},
                    model: {},
                    rules: {},
                    items: {!! printArray(old($field->get('name'),$field->getValue($model))) !!},
                    isEdit: false,
                    editIndex: false,
                    videoMediaPickerOptions: {!! printArray($field->getVideoMediaPickerOptions()) !!},
                    previewMediaPickerOptions: {!! printArray($field->getPreviewMediaPickerOptions()) !!},
                    functions: {}
                }
            },
            created() {
                this.init();
                @if (is_field_translatable($model, $field))
                this.updateLocaleData(this.items);
                @endif
            },
            mounted() {
                vueFieldInstances['{{$vue_instance_name}}'] = this;
            },
            methods: {
                init() {
                    var _this = this;
                    _this.fields.forEach(function (field) {
                        _this.model[field.name] = field.default;
                        _this.rules[field.name] = field.rules;
                    })
                    _this.model[_this.videoMediaPickerOptions.name] = '';
                    _this.model[_this.previewMediaPickerOptions.name] = '';
                    _this.rules[_this.videoMediaPickerOptions.name] = { "required" : true, "message" : "Обязательное поле", "trigger" : "blur" };
                    _this.rules[_this.previewMediaPickerOptions.name] = { "required" : true, "message" : "Обязательное поле", "trigger" : "blur" };
                    _this.functions['displayValue'] = new Function("item", {!! printString($field->get('displayValue')) !!});

                    if(this.items.length < this.minItems){
                        let n = this.minItems-this.items.length;
                        for (let i = 0; i < n; i++)
                            this.items.push({...this.model});
                    }

                },
                openFormDialog(){
                    this.formDialogVisibility = true;
                },
                closeFormDialog(){
                    this.formDialogVisibility = false;
                },
                openVideoMediaPicker() {
                    this.$refs.videoDialog.init(this.videoMediaPickerOptions);
                },
                openPreviewMediaPicker() {
                    this.$refs.previewDialog.init(this.previewMediaPickerOptions);
                },
                addItem() {
                    if (this.items.length < this.maxItems)
                    {
                        this.offEdit();
                        this.openFormDialog();
                    }
                    else
                        toastr.warning(lang.choice('validation.max_items',this.maxItems));
                },
                editItem(key) {
                    let _this = this;
                    this.isEdit = true
                    this.editIndex = key
                    _this.fields.forEach(function (field) {
                        _this.model[field.name] = _this.items[key][field.name];
                    });
                    this.model[this.videoMediaPickerOptions.name] = this.items[key][this.videoMediaPickerOptions.name];
                    this.model[this.previewMediaPickerOptions.name] = this.items[key][this.previewMediaPickerOptions.name];
                    this.openFormDialog()
                },
                saveItem() {
                    this.$refs.vueForm.validate((valid) => {
                        if (valid) {
                            if(typeof this.editIndex === 'number')
                                this.items[this.editIndex] = {...this.model}
                            else
                                this.items.push({...this.model})
                            this.closeFormDialog()
                        } else {
                            return false;
                        }
                    });
                },
                deleteItem(key) {
                    if(this.items.length > this.minItems)
                        this.items.splice(key, 1);
                    else
                        toastr.warning(lang.choice('validation.min_items',this.maxItems));
                },
                onClosed(){
                    this.offEdit();
                    this.resetValidation();
                },
                offEdit(){
                    this.isEdit = false
                    this.editIndex = false
                    this.resetModel()
                },
                resetValidation(){
                    this.$refs.vueForm.resetFields()
                },
                resetModel(){
                    let _this = this;
                    _this.fields.forEach(function (field) {
                        _this.model[field.name] = field.default;
                    });
                    this.model[this.videoMediaPickerOptions.name] = '';
                    this.model[this.previewMediaPickerOptions.name] = '';
                },
                // important
                @if (is_field_translatable($model, $field))
                updateLocaleData(items) {
                    this.items = this.isJsonValid(items) ? JSON.parse(items) : (items ? items : [])
                }
                @endif
            }
        }).mount('#{{ $field->getId() }}');;
    </script>
@endpush

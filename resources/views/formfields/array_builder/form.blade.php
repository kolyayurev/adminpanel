@php($vue_instance_name = vue_instance_name($field, $model))

<div class="mb-3">
    <div class="form-group array-builder-field" id="{{ $field->getId() }}" v-cloak>
        <label for="{{ name2id($field->get('name')) }}">{{ $field->get('label') }}</label>
        @include('adminpanel::multilingual.input-hidden-form')
        <input type="hidden" name="{{$field->get('name')}}"
               class="form-control is-vue"
               :value="printObject(items)"
               data-vue-instance="{{ $vue_instance_name }}"
        />
        <el-button class="array-builder-field__add-button" @click="addItem" type="primary" size="small">
            <i class="bi bi-plus-lg"></i>&nbsp;
            @{{ lang.get('common.add') }}
        </el-button>
        <draggable v-if="items.length" v-model="items" item-key="{{ $field->get('defaultFieldName') }}" class="list-items">
            <template #item="{element,index}">
                <div class="list-item">
                    <div class="list-item__title"><i class="bi bi-list"></i> @{{  functions.displayValue(element) }}</div>
                    <div class="list-item__buttons">
                        <el-button class="button" type="primary" @click="editItem(index)" size="small"><i
                                class="bi bi-pencil-square"></i></el-button>
                        <el-button class="button" type="danger" @dblclick="deleteItem(index)" size="small"><i
                                class="bi bi-trash3"></i></el-button>
                    </div>
                </div>
            </template>
        </draggable>
        <div v-else class="list-item-empty"></div>
        <el-dialog
            class="dialog --form"
            v-model="formDialogVisibility"
            :title="lang.get('common.'+(isEdit?'edit':'add'))"
            top="10vh"
            @closed="onClosed()"
        >
            <el-form
                :model="model"
                :rules="rules"
                ref="vueForm"
                label-position="top">
                <el-row :gutter="10">
                    <el-col v-for="field in fields" :md="field.col" :key="field.name">
                        <el-form-item :label="field.label" :prop="field.name">
                            <component :is="field.component"
                                       v-bind="field.props"
                                       v-model="model[field.name]"
                                {{--                                   @keydown.enter.prevent="isEdit?saveItem():addItem()"--}}
                            >
                            </component>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
            <template #footer>
                <el-button @click="saveItem"><i :class="'bi bi-'+(isEdit?'check':'plus')"></i>@{{ lang.get('common.'+(isEdit?'save':'add')) }}</el-button>
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
                    functions: {}
                }
            },
            created() {
                this.init();
                @if (is_field_translatable($model, $field))
                    this.updateLocaleData(this.items)  // important
                @endif
            },
            mounted() {
                vueFieldInstances['{{$vue_instance_name}}'] = this  // important
            },
            methods: {
                init() {
                    var _this = this;
                    _this.fields.forEach(function (field) {
                        _this.model[field.name] = field.default;
                        _this.rules[field.name] = field.rules;
                    })
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
                addItem() {
                    if (this.items.length < this.maxItems)
                    {
                        this.offEdit()
                        this.openFormDialog()
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
                },
                @if (is_field_translatable($model, $field))
                updateLocaleData(items) {
                    this.items = this.isJsonValid(items) ? JSON.parse(items) : (items ? items : [])
                }
                @endif
            }
        }).mount('#{{ $field->getId() }}');
    </script>
@endpush

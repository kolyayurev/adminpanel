<template>
<div>
    <el-dialog
        class="dialog"
        v-model="dialogVisibility"
        :title="options.label"
        top="10vh"
    >
        <v-media-manager
            ref="media_manager"
            v-bind="options"
            v-model="localValue"
            @added-file="addedFile()"
        ></v-media-manager>
        <template #footer class="mt-2">
            <el-button type="primary" @click="closeDialog">{{ lang.get('common.save') }}</el-button>
        </template>
    </el-dialog>
</div>
</template>

<script>

import vModel from '../mixins/v-model';
import {dialog} from '../mixins/dialog'

export default {
    mixins: [dialog, vModel],
    name: 'v-dialog-media-picker',
    props: {
        closeAfterSelection: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            options: {},
        }
    },
    created() {
        this.setDefaultOptions();
    },
    methods: {
        addedFile(){
            if(this.closeAfterSelection && !this.options.allowMultiSelect)
                this.closeDialog();
        },
        getSelectedFilesData() {
            return this.$refs.media_manager ? this.$refs.media_manager.getSelectedFilesData() : [];
        },
        init(options) {
            this.setOptions(options).openDialog();
        },
        setOptions(options) {
            this.options = {...this.options, ...options};
            return this;
        },
        setContent(content) {
            this.content = content;
            return this;
        },
        setDefaultOptions() {
            this.options = {
                label: '',
                basePath: '/',
                filename: null,
                allowMultiSelect: false,
                max: 0,
                min: 0,
                showFolders: false,
                showToolbar: true,
                allowUpload: true,
                allowMove: false,
                allowDelete: true,
                allowCreateFolder: true,
                allowRename: true,
                allowCrop: true,
                allowedTypes: [],
                accept: '',
                preSelect: false,
                expanded: false,
                showExpandButton: true,
                element: '',
                fieldName: '',
            }
        }
    }
}
</script>

<style lang="scss">


</style>

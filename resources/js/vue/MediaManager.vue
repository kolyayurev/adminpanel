<template>
    <el-card class="media-manager" shadow="never" v-loading="loading" :element-loading-text="lang.get('media.loading')">

        <draggable v-if="hiddenElement" :list="allowMultiSelect?localValue:(localValue?[localValue]:[])"
                   item-key="name" class="media-manager__files">
            <template #item="{element}">
                <v-files-item :file="element" allow-delete @delete="removeFileFromInput(element)"></v-files-item>
            </template>
        </draggable>

        <el-button
            class="w-100"
            type="info"
            v-if="hiddenElement"
            @click="isExpanded = !isExpanded;">
            <span v-if="!isExpanded"><i class="bi bi-chevron-double-down"></i> {{ lang.get('common.open') }}</span>
            <span v-if="isExpanded"><i class="bi bi-chevron-double-up"></i> {{ lang.get('common.close') }}</span>
        </el-button>
        <div class="media-manager__toolbar" id="toolbar" v-if="showToolbar"
             :style="isExpanded ? 'display:block' : 'display:none'">
            <el-button-group class="me-1">
                <el-button type="primary" v-if="allowUpload"
                           @click="dropzone.element.click()">
                    <i class="bi bi-upload"></i>&nbsp;
                    {{ lang.get('common.upload') }}

                </el-button>
                <el-button type="primary"
                           v-if="allowCreateFolder"
                           @click="createFolder()">
                    <i class="bi bi-folder-plus"></i>&nbsp;
                    {{ lang.get('media.add_folder') }}
                </el-button>
            </el-button-group>
            <el-button
                v-if="isFullMode"
                class="me-1"
                @click="getFiles()">
                <i class="bi bi-arrow-clockwise"></i>
            </el-button>
            <el-button-group class="me-1">
                <el-button
                    :disabled="selectedFiles.length === 0" v-if="allowUpload && hiddenElement && allowMultiSelect"
                    @click="addSelectedFiles()">
                    <i class="bi bi-upload"></i>&nbsp;
                    {{ lang.get('media.add_all_selected') }}
                </el-button>
                <el-button
                    v-if="showFolders && allowMove"
                    @click="openMoveDialog()">
                    <i class="bi bi-arrows-move"></i>&nbsp;
                    {{ lang.get('common.move') }}
                </el-button>

                <el-button
                    v-if="allowCrop"
                    :disabled="selectedFiles.length !== 1 || !fileIs(selectedFile, 'image')"
                    @click="openCropDialog(selectedFile)"
                >
                    <i class="bi bi-crop"></i>&nbsp;
                    {{ lang.get('media.crop') }}
                </el-button>
            </el-button-group>
            <el-popconfirm
                width="220"
                :confirm-button-text="lang.get('common.delete_confirm')"
                :cancel-button-text="lang.get('common.cancel')"
                @confirm="deleteFiles(selectedFiles)"
                :title="lang.get('media.delete_question')"
                v-if="allowDelete"
            >
                <template #reference>
                    <el-button
                        type="danger"
                        :disabled="selectedFiles.length === 0"
                    >
                        <i class="bi bi-trash3"></i>&nbsp;
                        {{ lang.get('common.delete') }}
                    </el-button>
                </template>
            </el-popconfirm>
        </div>
        <div ref="dropzone" class="d-none" v-if="allowUpload"></div>
        <div id="uploadPreview" class="d-none" v-if="allowUpload"></div>
        <el-progress class="media-manager__progress" :percentage="progress" :show-text="false" v-if="allowUpload"
                     v-show="progressBar"/>
        <el-breadcrumb class="media-manager__breadcrumbs" separator=">" v-show="isExpanded">
            <el-breadcrumb-item @click="setCurrentPath(-1)"><strong>{{ lang.get('media.library') }}</strong>
            </el-breadcrumb-item>
            <el-breadcrumb-item
                v-for="(folder, i) in getCurrentPath()" @click="setCurrentPath(i)"
            >
                {{ folder }}
            </el-breadcrumb-item>
        </el-breadcrumb>
        <el-row class="media-manager__content" v-show="isExpanded">
            <el-col :sm="16" :md="18">
                <div class="media-manager__files" id="files" v-if="files.length">
                    <template v-for="file in files">
                        <el-card :class="'files-item '+ (isFileSelected(file) ? 'selected' : '')"
                                 @click="selectFile(file, $event)" @dblclick="openFile(file)" v-if="filter(file)"
                                 shadow="never">
                            <el-row :gutter="20">
                                <el-col :span="8" class="files-item__icon">
                                    <!--                                    TODO:refactor-->
                                    <!--                                    <v-media-icon class="media-picker-field__single-img" :file="file.relative_path"/>-->
                                    <template v-if="fileIs(file, 'image')">
                                        <el-image
                                            class="files-item__image"
                                            :src="file.path"
                                            fit="cover"
                                        />
                                    </template>
                                    <template v-else-if="fileIs(file, 'video')">
                                        <i class="bi bi-camera-video"></i>
                                    </template>
                                    <template v-else-if="fileIs(file, 'audio')">
                                        <i class="bi bi-music-note-beamed"></i>
                                    </template>
                                    <template v-else-if="fileIs(file, 'zip')">
                                        <i class="bi bi-file-earmark-zip"></i>
                                    </template>
                                    <template v-else-if="fileIs(file, 'folder')">
                                        <i class="bi bi-folder"></i>
                                    </template>
                                    <template v-else>
                                        <i class="bi file-text"></i>
                                    </template>
                                </el-col>
                                <el-col :span="16" class="files-item__details">
                                    <div :class="file.type">
                                        <h6>{{ file.name }}</h6>
                                        <small v-if="!fileIs(file, 'folder')">
                                            <span class="file_size">{{ bytesToSize(file.size) }}</span>
                                        </small>
                                    </div>
                                </el-col>
                            </el-row>
                        </el-card>
                    </template>
                </div>
                <el-empty v-if="files.length === 0" :description="lang.get('media.no_files_in_folder')"/>
            </el-col>
            <el-col :sm="8" :md="6" class="media-manager__aside">
                <transition name="fade">
                    <!--                    TODO: make styles -->
                    <div v-if="selectedFiles.length > 1" class="right_none_selected">
                        <i class="bi bi-list-task"></i>
                        <p>{{ selectedFiles.length }} {{ lang.get('media.files_selected') }}</p>
                    </div>
                    <div v-else-if="selectedFiles.length === 1" class="details">
                        <div class="details__icon">
                            <div v-if="fileIs(selectedFile, 'image')">
                                <el-image
                                    class="media-manager__image-show"
                                    :src="selectedFile.path"
                                    fit="cover"
                                    lazy
                                    :preview-src-list="[selectedFile.path]"
                                    hide-on-click-modal
                                     />
                                    <div class="media-manager__thumbnails" v-if="selectedFile.thumbnails.length > 0">
                                        <div class="thumbnail" v-for="thumbnail in selectedFile.thumbnails">
                                            <el-image
                                                :src="temporaryPath(thumbnail.path)"
                                                fit="contain"
                                                lazy
                                                :preview-src-list="[temporaryPath(thumbnail.path)]"
                                                hide-on-click-modal
                                                :title="thumbnail.thumb_name + ' ' +getThumbnailSizes(thumbnail.thumb_name)"
                                            />
                                            <div class="thumbnail__actions">
                                                <el-button
                                                    circle
                                                    type="primary"
                                                    v-if="getThumbnailType(thumbnail.thumb_name) === 'crop'"
                                                    @click="openCropDialog(selectedFile,thumbnail.thumb_name)"
                                                ><i class="bi bi-crop"></i>
                                                </el-button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div v-else-if="fileIs(selectedFile, 'video')">
                                <video  ref="videoplayer" controls>
                                    <source :src="selectedFile.path" type="video/mp4"/>
                                    <source :src="selectedFile.path" type="video/ogg"/>
                                    <source :src="selectedFile.path" type="video/webm"/>
                                    {{ lang.get('media.browser_video_support') }}
                                </video>
                            </div>
                            <div v-else-if="fileIs(selectedFile, 'audio')">
                                <i class="bi bi-music-note-beamed"></i>
                                <audio controls style="width:100%; margin-top:5px;" ref="audioplayer">
                                    <source :src="selectedFile.path" type="audio/ogg"/>
                                    <source :src="selectedFile.path" type="audio/mpeg"/>
                                    {{ lang.get('media.browser_audio_support') }}
                                </audio>
                            </div>
                            <div v-else-if="fileIs(selectedFile, 'zip')">
                                <i class="bi bi-file-earmark-zip"></i>
                            </div>
                            <div v-else-if="fileIs(selectedFile, 'folder')">
                                <i class="bi bi-folder"></i>
                            </div>
                            <div v-else>
                                <i class="bi bi-file-text"></i>
                            </div>
                        </div>
                        <el-collapse v-model="activeDetails">
                            <el-collapse-item :title="lang.get('media.details')" name="details">
                                <div class="details__info">
                                    <el-row :gutter="10">
                                        <el-col class="mb-1">
                                            <h5>{{ lang.get('media.title') }}:</h5>
                                            <el-input v-if="allowRename" type="text" v-model="newName"
                                                      @keydown.enter.prevent=""
                                                      @keyup.enter.prevent="renameFile">
                                            </el-input>
                                            <p v-else>{{ selectedFile.name }}</p>
                                        </el-col>
                                        <template v-if="!fileIs(selectedFile, 'folder')">
                                            <el-col v-if="selectedFile.type" class="mt-1" :md="12">
                                                <h5>{{ lang.get('media.type') }}:</h5>
                                                <p>{{ selectedFile.type }}</p>
                                            </el-col>
                                            <el-col class="mt-1" :md="12">
                                                <h5>{{ lang.get('media.size') }}:</h5>
                                                <p><span class="selected_file_size">{{ bytesToSize(selectedFile.size) }}</span>
                                                </p>
                                            </el-col>
                                            <el-col>
                                                <h5>{{ lang.get('media.public_url') }}:</h5>
                                                <p><a :href="selectedFile.path" target="_blank">{{ lang.get('common.click_here') }}</a></p>
                                            </el-col>
                                            <el-col>
                                                <h5>{{ lang.get('media.last_modified') }}:</h5>
                                                <p>{{ dateFilter(selectedFile.last_modified) }}</p>
                                            </el-col>
                                        </template>

                                        <el-col v-if="fileIs(selectedFile, 'image') && selectedFile.thumbnails.length > 0">
                                            <h5> {{ lang.get('media.thumbnails') }}</h5>
                                            <ul>
                                                <li v-for="thumbnail in selectedFile.thumbnails">
                                                    <a :href="thumbnail.path" target="_blank">
                                                        {{ thumbnail.thumb_name }} {{ getThumbnailSizes(thumbnail.thumb_name) }}
                                                    </a>
                                                    <el-link type="primary"
                                                             v-if="getThumbnailType(thumbnail.thumb_name) === 'crop'"
                                                             @click="openCropDialog(selectedFile,thumbnail.thumb_name)"><i
                                                        class="bi bi-pencil-square"></i></el-link>
                                                    <el-popconfirm
                                                        width="220"
                                                        :confirm-button-text="lang.get('common.delete_confirm')"
                                                        :cancel-button-text="lang.get('common.cancel')"
                                                        @confirm="deleteFiles([thumbnail])"
                                                        :title="lang.get('media.delete_question')"
                                                        v-if="allowDelete && false"
                                                    >
                                                        <template #reference>
                                                            <el-link type="danger"><i class="bi bi-trash3"></i></el-link>
                                                        </template>
                                                    </el-popconfirm>
                                                </li>
                                            </ul>
                                        </el-col>
                                    </el-row>
                                </div>
                            </el-collapse-item>
                        </el-collapse>
                    </div>

                    <div v-else class="right_none_selected">
                        <i class="bi bi-cursor"></i>
                        <p>{{ lang.get('media.nothing_selected') }}</p>
                    </div>
                </transition>
            </el-col>
        </el-row>

        <!-- Move Files Modal -->
        <el-dialog
            v-model="moveDialog"
            :title="lang.get('media.move_file_folder')"
            width="30%"
        >
            <h4>{{ lang.get('media.destination_folder') }}</h4>
            <el-select v-model="modals.moveFiles.destination" class="m-2"
                       :placeholder="lang.get('media.destination_folder')">
                <el-option value="" disabled>{{ lang.get('media.destination_folder') }}</el-option>
                <el-option v-if="currentFolder !== basePath && showFolders" value="/../">../</el-option>
                <el-option
                    v-for="(folder,idx) in folders"
                    :key="idx"
                    :label="folder.name"
                    :value="currentFolder+'/'+folder.name"
                />
            </el-select>
            <template #footer>
              <span class="dialog-footer">
                <el-button @click="closeMoveDialog">
                    {{ lang.get('common.cancel') }}
                </el-button>
                <el-button type="primary" @click="moveFiles">
                  {{ lang.get('common.move') }}
                </el-button>
              </span>
            </template>
        </el-dialog>
        <!-- End Move File Modal -->

        <!-- Crop Image Modal -->
        <el-dialog
            class="dialog --crop"
            v-if="allowCrop"
            v-model="cropDialog"
            :title="lang.get('media.crop_image')"
            @opened="openedCropDialog"
            @closed="closedCropDialog"
            top="10vh"
        >
            <div class="crop-container">
                <img ref="croppingImage"
                     v-if="fileIs(cropImage, 'image')"
                     class="img-fluid"
                     :src="cropImage.path + '?' + new Date()"/>
            </div>
            <div class="new-image-info" v-show="false">
                {{ lang.get('media.width') }} <span>{{ crop.width + 'px' }}</span>,
                {{ lang.get('media.height') }}<span>{{ crop.height + 'px' }}</span>
            </div>
            <template #footer>
              <span class="dialog-footer">
                <el-button @click="closeCropDialog">
                    {{ lang.get('common.cancel') }}
                </el-button>
                  <el-popconfirm

                      width="300"
                      :confirm-button-text="lang.get('common.yes')"
                      :cancel-button-text="lang.get('common.no')"
                      :title="lang.get('media.crop_override_confirm')"
                      @confirm="cropFile(false)">
                    <template #reference>
                      <el-button type="primary">
                          {{ lang.get('media.crop') }}
                        </el-button>
                    </template>
                  </el-popconfirm>

                <el-button v-if="!crop.name" type="primary" @click="cropFile(true)">
                  {{ lang.get('media.crop_and_create') }}
                </el-button>
              </span>
            </template>
        </el-dialog>
    </el-card>
</template>

<script>
export default {
    name: 'v-media-manager',
    props: {
        mode: {
            type: String,
            default: 'full'
        },
        basePath: {
            type: String,
            default: '/'
        },
        filename: {
            type: String,
            default: null
        },
        allowMultiSelect: {
            type: Boolean,
            default: true
        },
        allowUpload: {
            type: Boolean,
            default: true
        },
        allowMove: {
            type: Boolean,
            default: true
        },
        allowDelete: {
            type: Boolean,
            default: true
        },
        allowCreateFolder: {
            type: Boolean,
            default: true
        },
        allowRename: {
            type: Boolean,
            default: true
        },
        allowCrop: {
            type: Boolean,
            default: true
        },
        showFolders: {
            type: Boolean,
            default: true
        },
        showToolbar: {
            type: Boolean,
            default: true
        },
        max: {
            type: Number,
            default: 0
        },
        min: {
            type: Number,
            default: 0
        },
        allowedTypes: {
            type: Array,
            default: function () {
                return [];
            }
        },
        accept: {
            type: String,
            default: ""
        },
        preSelect: {
            type: Boolean,
            default: true,
        },
        element: {
            type: String,
            default: ""
        },
        expanded: {
            type: Boolean,
            default: true,
        },
        detailsExpanded :{
            type: Boolean,
            default: true,
        },
        thumbnails: {
            type: Array,
            default: function () {
                return [];
            }
        },
        hideThumbnails:{
            type: Boolean,
            default: false,
        },
        modelValue: {
            validator: v => true,
            required: false,
        },
    },
    data: function () {
        return {
            progressBar: false,
            progress: 0,
            currentFolder: this.basePath,
            selectedFiles: [],
            files: [],
            loading: true,
            hiddenElement: null,
            isExpanded: this.expanded,
            moveDialog: false,
            cropDialog: false,
            newName: null,
            dropzone: null,
            cropper: null,
            cropImage: null,
            crop: {
                minWidth: null,
                minHeight: null,
                height: 0,
                width: 0,
                x: 0,
                y: 0
            },
            modals: {
                newFolder: {
                    name: ''
                },
                moveFiles: {
                    destination: ''
                }
            },

        };
    },
    computed: {
        isFullMode() {
            return this.mode === 'full';
        },
        selectedFile: function () {
            return this.selectedFiles[0];
        },
        folders() {
            let _this = this;
            return this.files.filter(function (file) {
                return file && file.type === 'folder' && !_this.selectedFiles.includes(file)
            })
        },
        localValue: {
            get() {
                return this.modelValue;
            },
            set(localState) {
                this.$emit('update:modelValue', localState);
            },
        },
        activeDetails(){
            return this.detailsExpanded?'details':'';
        }
    },
    watch: {
        localValue: {
            handler(newValue, oldValue) {
                this.hiddenElement.value = Array.isArray(newValue) ? JSON.stringify(newValue) : newValue;
            },
            deep: true,
        },
        selectedFiles: function () {
            this.newName = this.selectedFiles.length ? this.selectedFiles[0].name : '';
        }
    },
    methods: {
        createFolder() {
            if (!this.allowCreateFolder) {
                return;
            }
            let _this = this;

            _this.$prompt(lang.get('media.new_folder_name'), lang.get('media.add_new_folder'), {
                confirmButtonText: lang.get('media.create_new_folder'),
                cancelButtonText: lang.get('common.cancel'),
                inputPattern:
                    /^.+$/,
                inputErrorMessage: lang.get('validation.required'),
            })
                .then(({value}) => {
                    $.post(route('adminpanel.media.new-folder'), {
                            new_folder: _this.currentFolder + '/' + value,
                        }, function (data) {
                            if (data.status) {
                                toastr.success(lang.get('common.successfully_created') + name, lang.get('common.sweet_success'));
                                _this.getFiles();
                            } else {
                                toastr.error(data.error, lang.get('common.whoopsie'));
                            }
                        }
                    );
                })
        },
        openMoveDialog() {
            this.moveDialog = true;
        },
        closeMoveDialog() {
            this.moveDialog = false;
        },
        openCropDialog(image, thumbnailName = '') {
            let settings = this.getThumbnailSettings(thumbnailName);
            if (typeof settings !== 'undefined') {
                this.crop.minWidth = settings.width;
                this.crop.minHeight = settings.height;
                this.crop.name = settings.name;
            }
            this.cropImage = image;
            this.cropDialog = true;
        },
        openedCropDialog() {
            if (this.allowCrop) {
                let _this = this;

                if (typeof _this.cropper !== 'undefined' && _this.cropper instanceof Cropper) {
                    _this.cropper.destroy();
                }

                _this.cropper = new Cropper(_this.$refs.croppingImage, {
                    strict: true,
                    responsive: true,
                    minContainerWidth:0,
                    minContainerHeight:0,
                    minCropBoxWidth: _this.crop.minWidth,
                    minCropBoxHeight: _this.crop.minHeight,
                    aspectRatio: _this.crop.minWidth / _this.crop.minHeight,
                    zoomable: false,
                    movable:false,
                    autoCropArea: 1,
                    // viewMode: 2,
                    crop: function (e) {
                        _this.crop.width = Math.round(e.detail.width);
                        _this.crop.height = Math.round(e.detail.height);
                        _this.crop.x = Math.round(e.detail.x)
                        _this.crop.y = Math.round(e.detail.y)
                    }
                });
            }
        },
        closedCropDialog() {
            this.crop.minWidth = null;
            this.crop.minHeight = null;
            this.crop.width = null;
            this.crop.height = null;
            this.crop.name = null;
            this.crop.x = null;
            this.crop.y = null;
            this.cropper.destroy()
        },
        closeCropDialog() {
            this.cropDialog = false;
        },
        getFiles: function () {
            let _this = this;
            _this.loading = true;
            $.post(route('adminpanel.media.files'), {
                    folder: _this.currentFolder,
                    thumbnails: JSON.stringify(_this.thumbnails),
                    hide_thumbnails: _this.hideThumbnails
                }, function (data) {
                    _this.files = [];
                    for (let i = 0, file; file = data[i]; i++) {
                        if (_this.filter(file)) {
                            _this.files.push(file);
                        }
                    }
                    _this.selectedFiles = [];
                    if (_this.preSelect && data.length > 0) {
                        _this.selectedFiles.push(data[0]);
                        //TODO: add file to result value if empty
                        // _this.addFileToInput(data[0]);
                    }
                    _this.loading = false;
                }
            )
            ;
        },
        selectFile: function (file, e) {
            if ((!e.ctrlKey && !e.metaKey && !e.shiftKey) || !this.allowMultiSelect) {
                this.selectedFiles = [];
            }

            if (e.shiftKey && this.allowMultiSelect && this.selectedFiles.length === 1) {
                let index = null;
                let start = 0;
                for (let i = 0, cfile; cfile = this.files[i]; i++) {
                    if (cfile === this.selectedFile) {
                        start = i;
                        break;
                    }
                }

                let end = 0;
                for (let i = 0, cfile; cfile = this.files[i]; i++) {
                    if (cfile === file) {
                        end = i;
                        break;
                    }
                }

                for (let i = start; i < end; i++) {
                    index = this.selectedFiles.indexOf(this.files[i]);
                    if (index === -1) {
                        this.selectedFiles.push(this.files[i]);
                    }
                }
            }

            let index = this.selectedFiles.indexOf(file);
            if (index === -1) {
                this.selectedFiles.push(file);
            }

            if (this.selectedFiles.length === 1) {
                let _this = this;
                _this.$nextTick(function () {
                    if (_this.fileIs(_this.selectedFile, 'video')) {
                        _this.$refs.videoplayer.load();
                    } else if (_this.fileIs(_this.selectedFile, 'audio')) {
                        _this.$refs.audioplayer.load();
                    }
                });
            }
        },
        openFile: function (file) {
            if (file.type === 'folder') {
                this.currentFolder += file.name + "/";
                this.getFiles();
            } else if (this.hiddenElement) {
                this.addFileToInput(file);
            }
        },
        isFileSelected: function (file) {
            return this.selectedFiles.includes(file);
        },

        getCurrentPath: function () {
            let path = this.currentFolder.replace(this.basePath, '').split('/').filter(function (el) {
                return el != '';
            });

            return path;
        },
        setCurrentPath: function (i) {
            if (i === -1) {
                this.currentFolder = this.basePath;
            } else {
                let path = this.getCurrentPath();
                path.length = i + 1;
                this.currentFolder = this.basePath + path.join('/') + '/';
            }

            this.getFiles();
        },
        filter(file) {
            if (typeof file !== 'undefined') {
                if (this.allowedTypes.length > 0) {
                    if (file.type !== 'folder') {
                        for (let i = 0, type; type = this.allowedTypes[i]; i++) {
                            if (file.type.indexOf(type) > -1) {
                                return true;
                            }
                        }
                    }
                }

                if (file.type === 'folder' && this.showFolders) {
                    return true;
                } else if (file.type === 'folder' && !this.showFolders) {
                    return false;
                }
                if (this.allowedTypes.length === 0) {
                    return true;
                }
            }


            return false;
        },
        addFileToInput(file) {
            if (file.type !== 'folder') {
                if (!this.allowMultiSelect) {
                    this.localValue = file.relative_path;
                } else {
                    let content = this.localValue;
                    if (content.indexOf(file.relative_path) !== -1) {
                        return;
                    }
                    if (content.length >= this.max && this.max > 0) {
                        let msg_sing = lang.choice('media.max_files_select', 1);
                        let msg_plur = lang.choice('media.max_files_select', 2);
                        if (this.max === 1) {
                            toastr.error(msg_sing);
                        } else {
                            toastr.error(msg_plur.replace('2', this.max));
                        }
                    } else {
                        content.push(file.relative_path);
                        this.localValue = content;
                    }
                }
                this.$emit('addedFile')
                this.$forceUpdate();
            }
        },
        removeFileFromInput(path) {
            if (this.allowMultiSelect) {
                let content = this.localValue;
                if (content.indexOf(path) !== -1) {
                    content.splice(content.indexOf(path), 1);
                    this.localValue = content;
                    this.$forceUpdate();
                }
            } else {
                // this.hiddenElement.value = '';
                this.localValue = '';
                this.$forceUpdate();
            }
        },
        clearFileFromInput() {
            this.localValue = this.allowMultiSelect ? [] : '';
            this.$forceUpdate();
        },
        getSelectedFiles: function () {
            if (!this.allowMultiSelect) {
                let content = [];
                if (this.localValue !== '') {
                    content.push(this.localValue);
                }
                return content;
            } else {
                return this.localValue;
            }
        },
        getSelectedFilesData() {
            return this.selectedFiles;
        },
        renameFile: function () {
            let _this = this;
            if (!_this.allowRename) {
                return;
            }
            $.post(route('adminpanel.media.rename'), {
                    folder_location: _this.currentFolder,
                    filename: _this.selectedFile.filename,
                    new_name: _this.newName,
                }, function (data) {
                    if (data.status) {
                        toastr.success(lang.get('media.success_renamed'), lang.get('common.sweet_success'));
                        _this.getFiles();
                    } else {
                        toastr.error(data.error, lang.get('common.whoopsie'));
                    }
                }
            )
            ;
        },
        deleteFiles(files) {
            if (!this.allowDelete) {
                return;
            }
            let _this = this;
            $.post(route('adminpanel.media.delete'), {
                    path: _this.currentFolder,
                    files: files,
                    thumbnails: JSON.stringify(_this.thumbnails),
                    // _token: csrf_token()
                }, function (data) {
                    if (data.status) {
                        files.forEach((file)=>{
                            _this.removeFileFromInput(file.relative_path);
                        })
                        toastr.success('', lang.get('common.sweet_success'));
                        _this.getFiles();
                    } else {
                        toastr.error(data.error, lang.get('common.whoopsie'));
                        _this.getFiles();
                    }
                }
            )
            ;
        },
        moveFiles() {
            if (!this.allowMove) {
                return;
            }
            let _this = this;
            let destination = this.modals.moveFiles.destination;
            if (destination === '') {
                return;
            }

            $.post(route('adminpanel.media.move'), {
                    path: _this.currentFolder,
                    files: _this.selectedFiles,
                    destination: destination,
                }, function (data) {
                    if (data.status) {
                        toastr.success(lang.get('media.success_moved'), lang.get('common.sweet_success')
                        )
                        ;
                        _this.getFiles();
                    } else {
                        toastr.error(data.error, lang.get('common.whoopsie'));
                    }

                    _this.modals.moveFiles.destination = '';
                }
            )
            ;
        },
        cropFile(mode = false) {
            if (!this.allowCrop) {
                return;
            }
            let _this = this;

            let postData = Object.assign(_this.crop);
            postData.originImageName = _this.cropImage.filename;
            postData.upload_path = _this.currentFolder;
            postData.create_mode = mode;
            postData.thumbnails = JSON.stringify(_this.thumbnails);

                $.post(route('adminpanel.media.crop'), _this.crop, function (data) {
                        if (data.status) {
                            toastr.success(data.message);
                            _this.getFiles();
                            _this.closeCropDialog();
                        } else {
                            toastr.error(data.error, lang.get('common.whoopsie'));
                        }
                    }
                )
            ;
        },
        getThumbnailSettings(name) {
            return this.thumbnails.find(t => t.name === name && t.type === 'crop');
        },
        getThumbnailType(name) {
            let thumb = this.getThumbnailSettings(name);
            return typeof thumb !== 'undefined' ? thumb.type : '';
        },
        getThumbnailSizes(name) {
            let thumb = this.getThumbnailSettings(name);
            return typeof thumb !== 'undefined' ? '('+thumb.width+'x'+thumb.height+')' : '';
        },
        addSelectedFiles: function () {
            let _this = this;
            for (let i = 0; i < _this.selectedFiles.length; i++) {
                _this.openFile(_this.selectedFiles[i]);
            }
        },
        bytesToSize: function (bytes) {
            let sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes === 0) return '0 Bytes';
            let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },
        dateFilter: function (date) {
            if (!date) {
                return null;
            }
            date = new Date(date * 1000);

            let month = "0" + (date.getMonth() + 1);
            let minutes = "0" + date.getMinutes();
            let seconds = "0" + date.getSeconds();

            return date.getFullYear() + '-' + month.substr(-2) + '-' + date.getDate() + ' ' + date.getHours() + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
        },
        close() {
            this.isExpanded = false
        },
        open() {
            this.isExpanded = true
        },
        init() {
            this.getFiles();
            let _this = this;

            if (this.element !== '') {
                this.hiddenElement = document.querySelector(this.element);
                if (!this.hiddenElement) {
                    console.error('Element "' + this.element + '" could not be found.');
                } else {
                    if (this.max > 1) {
                        if (this.localValue === '')
                            this.localValue = [];
                        if (!Array.isArray(this.localValue))
                            this.localValue = JSON.parse(this.localValue)

                    }
                }
            }

            //Dropzone
            if (this.allowUpload) {
                this.dropzone = new Dropzone(this.$refs.dropzone, {
                    timeout: 180000,
                    acceptedFiles: _this.accept,
                    url: route('adminpanel.media.upload'),
                    previewsContainer: "#uploadPreview",
                    totaluploadprogress: function (uploadProgress, totalBytes, totalBytesSent) {
                        _this.progress = uploadProgress;
                        if (uploadProgress === 100) {
                            _this.progressBar = false;
                            _this.progress = 0;
                        }
                    },
                    processing: function () {
                        _this.progressBar = true;
                    },
                    sending: function (file, xhr, formData) {
                        formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
                        formData.append("upload_path", _this.currentFolder);
                        formData.append("filename", _this.filename ?? '');
                        formData.append("thumbnails", JSON.stringify(_this.thumbnails));
                    },
                    success: function (e, res) {
                        if (res.status) {
                            toastr.success(res.message, lang.get('common.sweet_success'));
                        } else {
                            toastr.error(res.message, lang.get('common.whoopsie'));
                        }
                    },
                    error: function (e, res, xhr) {
                        toastr.error(res, lang.get('common.whoopsie'));
                    },
                    queuecomplete: function () {
                        _this.getFiles();
                    }
                });
            }

            $(document).ready(function () {
                $(".form-create-edit").submit(function (e) {
                    if (_this.hiddenElement) {
                        if (_this.max > 1) {
                            // let content = JSON.parse(_this.hiddenElement.value);
                            let content = _this.localValue;
                            if (content.length < _this.min) {
                                e.preventDefault();
                                let msg_sing = lang.choice('media.min_files_select', 1);
                                let msg_plur = lang.choice('media.min_files_select', 2);
                                if (_this.min === 1) {
                                    toastr.error(msg_sing);
                                } else {
                                    toastr.error(msg_plur.replace('2', _this.min));
                                }
                            }
                        } else {
                            // if (_this.min > 0 && _this.hiddenElement.value == '') {
                            if (_this.min > 0 && _this.localValue === '') {
                                e.preventDefault();
                                toastr.error(lang.choice('media.min_files_select', 1));
                            }
                        }
                    }
                });
            });
        }

    },

    mounted: function () {
        this.init();
        let _this = this;

        //Key events
        this.onkeydown = function (evt) {
            evt = evt || window.event;
            if (evt.keyCode === 39) {
                evt.preventDefault();
                for (let i = 0, file; file = _this.files[i]; i++) {
                    if (file === _this.selectedFile) {
                        i = i + 1; // increase i by one
                        i = i % _this.files.length;
                        _this.selectFile(_this.files[i], evt);
                        break;
                    }
                }
            } else if (evt.keyCode === 37) {
                evt.preventDefault();
                for (let i = 0, file; file = _this.files[i]; i++) {
                    if (file === _this.selectedFile) {
                        if (i === 0) {
                            i = _this.files.length;
                        }
                        i = i - 1;
                        _this.selectFile(_this.files[i], evt);
                        break;
                    }
                }
            } else if (evt.keyCode === 13) {
                evt.preventDefault();
                if (evt.target.tagName !== 'INPUT') {
                    _this.openFile(_this.selectedFile, null);
                }
            }
        };
    },
}
</script>

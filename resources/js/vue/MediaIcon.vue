<template>
    <template v-if="fileIs(file, 'image')">
        <el-image
            class="files-item__image"
            :src="storagePath(file)"
            fit="cover"
            :lazy="lazy"
            :preview-src-list="[storagePath(file)]"
            hide-on-click-modal
            ></el-image>
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
</template>

<script>
export default {
    name: "v-media-icon",
    props: {
        file: String,
        lazy: {
            type: Boolean,
            default: false
        }
    }
}
</script>


<template>
    <el-card class="files-item" shadow="never">
        <el-row :gutter="20">
            <el-col :span="8" class="files-item__icon">
                <v-media-icon :file="file"/>
            </el-col>
            <el-col :span="16" class="files-item__details">
                <div class="folder">
                    <h6>{{ getFileName(file) }}</h6>
                </div>
            </el-col>
        </el-row>
        <el-button v-if="allowDelete" class="files-item__remove" @dblclick="$emit('delete')" type="danger"><i class="bi bi-trash3"></i></el-button>
    </el-card>
</template>

<script>
export default {
    name: "v-files-item",
    props: {
        file: String,
        allowDelete: {
            type: Boolean,
            default: false
        }
    }
}
</script>


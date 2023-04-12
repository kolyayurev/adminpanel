import '@/lib';

import '@/bootstrap';

import '@/registration';

import { createApp } from 'vue';
import ElementPlus from 'element-plus'
import draggable from 'vuedraggable'
import MediaManager from './vue/MediaManager.vue'
import DialogMediaPicker from './vue/DialogMediaPicker.vue'
import MediaIcon from './vue/MediaIcon.vue'
import FilesItem from './vue/FilesItem.vue'
import GeoPicker from './vue/GeoPicker.vue'
import base from './mixins/base';

window.createVueApp = options => {
    const app = createApp(options)
    app.mixin(base);
    app.component(draggable.name,draggable);
    app.component(MediaManager.name,MediaManager);
    app.component(DialogMediaPicker.name,DialogMediaPicker);
    app.component(MediaIcon.name,MediaIcon);
    app.component(FilesItem.name,FilesItem);
    app.component(GeoPicker.name,GeoPicker);
    app.config.globalProperties.lang = lang;
    app.use(ElementPlus,{ size: 'default'})

    return app
}


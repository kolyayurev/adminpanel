import '@/lib';

import '@/bootstrap';

import '@/registration';

import { createApp } from 'vue';
import ElementPlus from 'element-plus'
import elEn from 'element-plus/es/locale/lang/en'
import elRu from 'element-plus/es/locale/lang/ru'
import draggable from 'vuedraggable'

// Локаль Element Plus = текущая локаль админки (глобальная `locale` из master.blade.php).
// Админка мультиязычна — не хардкодим; fallback на en.
const elementLocales = { en: elEn, ru: elRu }
const elementLocale = elementLocales[(typeof locale !== 'undefined' ? locale : 'en')] || elEn
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
    app.use(ElementPlus, { size: 'default', locale: elementLocale })

    return app
}


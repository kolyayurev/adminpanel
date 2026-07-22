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
import ChartWidget from './vue/ChartWidget.vue'
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
    app.component(ChartWidget.name,ChartWidget);
    app.config.globalProperties.lang = lang;
    app.use(ElementPlus, { size: 'default', locale: elementLocale })

    // Поля форм монтируются как createVueApp({...}).mount(selector) и нигде не хранят
    // ссылку на сам app — .unmount() потом вызвать нечем. Регистрируем по селектору,
    // чтобы модалка (datatable.blade.php) могла размонтировать поля формы при закрытии.
    const mount = app.mount.bind(app)
    app.mount = selector => {
        const result = mount(selector)
        window.__vueApps = window.__vueApps || {}
        window.__vueApps[selector] = app
        return result
    }

    return app
}


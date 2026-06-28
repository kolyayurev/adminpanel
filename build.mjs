// Сборка ассетов пакета adminpanel на Vite.
//
// Пакет не имеет своего app-скелета: собранные ассеты отдаёт PHP-роут `adminpanel.assets`
// по фиксированным путям из public/ (без манифеста и хешей), а blade подключает их как
// КЛАССИЧЕСКИЕ <script> (инлайновые скрипты в blade зависят от глобалей $/tinymce/… и
// сломались бы при type=module из-за defer). Поэтому каждый JS-entry собираем отдельной
// single-input сборкой в формате IIFE — самодостаточный файл без import/export.
// url()-ассеты (шрифты/иконки vendor-CSS) инлайним в data-URI: относительные пути в CSS,
// отдаваемом через роут, иначе не разрешаются (раньше mix жил с processCssUrls: false).

import { build } from 'vite';
import vue from '@vitejs/plugin-vue';
import autoprefixer from 'autoprefixer';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const watch = process.argv.includes('--watch');

// Удаляет пустой JS-чанк, который Vite генерирует для входа, состоящего только из SCSS
// (нужен лишь извлечённый CSS-файл).
function dropCssEntryJs(name) {
    return {
        name: 'adminpanel-drop-css-entry-js',
        generateBundle(_options, bundle) {
            for (const [file, chunk] of Object.entries(bundle)) {
                if (chunk.type === 'chunk' && chunk.isEntry && chunk.name === name) {
                    delete bundle[file];
                }
            }
        },
    };
}

const assetFileNames = (info) => {
    const name = info.names?.[0] ?? info.name ?? '';
    return name.endsWith('.css') ? 'css/[name][extname]' : 'assets/[name][extname]';
};

// Точки входа: app/docs → JS (+ свой CSS), element-ui → только CSS.
const targets = [
    { name: 'app', input: 'resources/js/app.js' },
    { name: 'docs', input: 'resources/js/docs.js' },
    { name: 'element-ui', input: 'resources/sass/element-ui/index.scss', cssOnly: true },
];

for (const target of targets) {
    await build({
        configFile: false,
        // Старые UMD/CommonJS-зависимости обращаются к Node-глобали `global`, которую webpack
        // полифилил, а Vite — нет. Подменяем на `globalThis`, иначе в браузере ReferenceError.
        define: { global: 'globalThis' },
        plugins: [vue(), ...(target.cssOnly ? [dropCssEntryJs(target.name)] : [])],
        resolve: {
            alias: { '@': path.resolve(__dirname, 'resources/js') },
        },
        css: {
            preprocessorOptions: {
                scss: {
                    // bare- и (после правок в SCSS) бывшие ~-импорты резолвятся из node_modules
                    loadPaths: ['node_modules'],
                    quietDeps: true,
                    silenceDeprecations: ['import', 'global-builtin', 'color-functions'],
                },
            },
            postcss: { plugins: [autoprefixer()] },
        },
        publicDir: false,
        build: {
            outDir: 'public',
            emptyOutDir: false, // в public/ лежат статические ассеты (datatables json, tinymce skins)
            manifest: false,
            assetsInlineLimit: () => true,
            chunkSizeWarningLimit: 5000,
            watch: watch ? {} : null,
            rollupOptions: {
                input: { [target.name]: path.resolve(__dirname, target.input) },
                output: {
                    format: 'iife',
                    inlineDynamicImports: true,
                    entryFileNames: 'js/[name].js',
                    assetFileNames,
                },
            },
        },
    });
}

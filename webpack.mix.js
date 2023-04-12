
const mix = require('laravel-mix');
const webpack = require('webpack');
const path = require('path');

mix
    .alias({
        '@': path.join(__dirname, 'resources/js'),
    })
     // Настройка webpack
    .webpackConfig({
        plugins: [
            new webpack.ContextReplacementPlugin(/moment[\\\/]locale$/, /^\.\/(en|ru)$/),
            new webpack.NormalModuleReplacementPlugin(/element-ui[\/\\]lib[\/\\]locale[\/\\]lang[\/\\]zh-CN/, 'element-ui/lib/locale/lang/ru-RU')
        ]
    })
    .options({ processCssUrls: false })
    .setPublicPath('public')
    .sass('resources/sass/element-ui/index.scss', 'public/css/element-ui.css')
    .sass('resources/sass/app.scss', 'public/css')
    .vue();
if (mix.inProduction()) {
    mix
        .js('resources/js/docs.js', 'public/js')
        .js('resources/js/app.js', 'public/js');
}
else {
    mix
        .js('resources/js/docs.js', 'public/js/docs-dev.js')
        .js('resources/js/app.js', 'public/js/app-dev.js');
}


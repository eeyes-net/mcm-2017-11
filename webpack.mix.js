let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js('resources/assets/js/admin.js', 'public/js')
    .sass('resources/assets/sass/admin.scss', 'public/css')
    .copy('node_modules/ckeditor/contents.css', 'public/dist/ckeditor/contents.css')
    .copy('node_modules/ckeditor/styles.js', 'public/dist/ckeditor/styles.js')
    .copyDirectory('node_modules/ckeditor/adapters', 'public/dist/ckeditor/adapters')
    .copyDirectory('node_modules/ckeditor/lang/zh-cn.js', 'public/dist/ckeditor/lang/zh-cn.js')
    .copyDirectory('node_modules/ckeditor/plugins', 'public/dist/ckeditor/plugins')
    .copyDirectory('node_modules/ckeditor/skins/moono-lisa', 'public/dist/ckeditor/skins/moono-lisa')
    .js('resources/assets/js/index.js', 'public/js')
    .sass('resources/assets/sass/index.scss', 'public/css')
    .js('resources/assets/js/home.js', 'public/js')
    .sass('resources/assets/sass/home.scss', 'public/css')
    .version()
    .sourceMaps()
;

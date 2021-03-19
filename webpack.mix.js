const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/backend.scss', 'public/css')
    .scripts([
        'public/assets/backend/libs/metismenu/metisMenu.min.js',
        'public/assets/backend/libs/simplebar/simplebar.min.js',
        'public/assets/backend/libs/node-waves/waves.min.js',
        // 'public/assets/backend/libs/apexcharts/apexcharts.min.js',
        // 'public/assets/backend/js/pages/dashboard.init.js',
        'public/assets/backend/js/app.js',
     ], 'public/js/backend.js')
     .scripts([
        'public/assets/backend/libs/metismenu/metisMenu.min.js',
        'public/assets/backend/libs/simplebar/simplebar.min.js',
        'public/assets/backend/libs/node-waves/waves.min.js',
        'public/assets/backend/libs/jquery.easing/jquery.easing.min.js',
        'public/assets/backend/libs/jquery-countdown/jquery.countdown.min.js',
        'public/assets/backend/libs/owl.carousel/owl.carousel.min.js',
        'public/assets/backend/js/pages/ico-landing.init.js',
        'public/assets/backend/js/app.js',
     ], 'public/js/frontend.js')
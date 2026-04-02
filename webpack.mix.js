const mix = require('laravel-mix')

mix.js('resources/js/site.js', 'public/js')
    .js('resources/js/admin.js', 'public/js')
    .styles([
        'resources/css/base.css',
        'resources/css/components/header.css',
        'resources/css/components/cards.css',
        'resources/css/pages/home.css',
    ], 'public/css/site.css')
    .styles([
        'resources/css/pages/admin.css',
    ], 'public/css/admin.css')
    .version()

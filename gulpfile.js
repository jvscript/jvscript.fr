const elixir = require('laravel-elixir');

//require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

//elixir(function (mix) {
////      mix.scripts(['app.js', 'controllers.js'], 'public/js/app.js')
////    mix.scriptsIn('public/js/mix');
// 
//});

elixir(function (mix) {
    mix.scripts([
        './public/js/mix/jquery.min.js',
        './public/js/mix/bootstrap.min.js',
        './public/js/mix/list.min.js',
        './public/js/mix/bootstrap-typeahead.min.js',
        './public/js/mix/blazy.min.js',
        './public/js/mix/confirm.min.js'
    ],
            'public/js/all.js');
});
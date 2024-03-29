const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix){
    mix.sass('app.scss')
        .sass(
            ['./resources/assets/sass/admin.scss','./resources/assets/sass/multi-select.scss'], 'public/css/admin.css')
        .webpack(['app.js','vue.js','jquery-3.2.1.js'])
        // .webpack(['app.js','jquery-3.2.1.js'])
        .scripts(['jquery.multi-select.js','wangEditor.js'],'public/js/jquery-plugin.js') ;
    //mix.compass();
});
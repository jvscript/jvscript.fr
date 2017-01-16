<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', 'JvscriptController@index')->name('home');

Route::get('/comment-installer', function () {
    return view('comment-installer');
});

Route::get('/ajout', function () {
    return view('script.ajout');
})->name("ajout-form");

Route::post('/ajout', 'JvscriptController@storeScript')->name('script.store');

Route::get('/script/{slug}', 'JvscriptController@showScript')->name('script.show');

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/developpeurs', function () {
    return view('developpeurs');
});





//Auth::routes();

//Route::get('/home', 'HomeController@index');

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

//home
Route::get('/', 'JvscriptController@index')->name('index');

Route::get('/search/{keyword}', 'JvscriptController@index')->name('search');

Route::get('/admin', 'JvscriptController@admin')->name('admin_index')->middleware('auth');

Route::get('/messcripts', 'JvscriptController@mesScripts')->name('messcripts')->middleware('auth');

//ajax-users
Route::get('/ajax-users', 'JvscriptController@ajaxUsers')->name('search')->middleware('auth');

//forms
Route::get('/script/ajout', 'JvscriptController@formScript')->name('script.form')->middleware('auth');
Route::get('/skin/ajout', 'JvscriptController@formSkin')->name('skin.form')->middleware('auth');
//form action (store in db)
Route::post('/script/ajout', 'JvscriptController@storeScript')->name('script.store')->middleware('auth');
Route::post('/skin/ajout', 'JvscriptController@storeSkin')->name('skin.store')->middleware('auth');

//show 1 item
Route::get('/script/{slug}', 'JvscriptController@showScript')->name('script.show');
Route::get('/skin/{slug}', 'JvscriptController@showSkin')->name('skin.show');

//install, note
Route::get('/script/install/{slug}', 'JvscriptController@installScript')->name('script.install');
Route::get('/script/note/{slug}/{note}', 'JvscriptController@noteScript')->name('script.note');
Route::get('/skin/install/{slug}', 'JvscriptController@installSkin')->name('skin.install');
Route::get('/skin/note/{slug}/{note}', 'JvscriptController@noteSkin')->name('skin.note');


//updates
Route::get('/script/{slug}/edit', 'JvscriptController@editScript')->name('script.edit')->middleware('auth');
Route::get('/skin/{slug}/edit', 'JvscriptController@editSkin')->name('skin.edit')->middleware('auth');
Route::put('/script/{slug}/edit', 'JvscriptController@updateScript')->name('script.update')->middleware('auth');
Route::put('/skin/{slug}/edit', 'JvscriptController@updateSkin')->name('skin.update')->middleware('auth');
//delete
Route::get('/script/{slug}/delete', 'JvscriptController@deleteScript')->name('script.delete');
Route::get('/skin/{slug}/delete', 'JvscriptController@deleteSkin')->name('skin.delete');

//validate script/skin
Route::get('/script/{slug}/validate', 'JvscriptController@validateScript')->name('script.validate');
Route::get('/skin/{slug}/validate', 'JvscriptController@validateSkin')->name('skin.validate');
Route::get('/script/{slug}/refuse', 'JvscriptController@refuseScript')->name('script.refuse');
Route::get('/skin/{slug}/refuse', 'JvscriptController@refuseSkin')->name('skin.refuse');

//contact form
Route::get('/contact/{message_body?}', function ($message_body = null) {
    return view('contact', ['message_body' => $message_body]);
})->name('contact.form');
//contact action
Route::post('/contact', 'JvscriptController@contactSend')->name('contact.send');


//static views 
Route::get('/developpeurs', function () {
    return view('statics.developpeurs');
});
Route::get('/aide', function () {
    return view('statics.comment-installer');
})->name('aide');


Route::get('/crawlInfo', 'JvscriptController@crawlInfo');

Auth::routes();


Route::get('auth/github', 'Auth\LoginController@redirectToProvider');
Route::get('auth/github/callback', 'Auth\LoginController@handleProviderCallback');

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

Route::get('/admin', 'JvscriptController@admin')->name('admin_index')->middleware('auth');

//forms
Route::get('/script/ajout', 'JvscriptController@formScript')->name('script.form');
Route::get('/skin/ajout', 'JvscriptController@formSkin')->name('skin.form');
//form action (store in db)
Route::post('/script/ajout', 'JvscriptController@storeScript')->name('script.store');
Route::post('/skin/ajout', 'JvscriptController@storeSkin')->name('skin.store');

//show 1 item
Route::get('/script/{slug}', 'JvscriptController@showScript')->name('script.show');
Route::get('/skin/{slug}', 'JvscriptController@showSkin')->name('skin.show');

//install, note
Route::get('/script/install/{slug}', 'JvscriptController@installScript')->name('script.install');
Route::get('/script/note/{slug}/{note}', 'JvscriptController@noteScript')->name('script.note');
Route::get('/skin/install/{slug}', 'JvscriptController@installSkin')->name('skin.install');
Route::get('/skin/note/{slug}/{note}', 'JvscriptController@noteSkin')->name('skin.note');


//updates
Route::get('/script/{slug}/edit', 'JvscriptController@editScript')->name('script.edit');
Route::get('/skin/{slug}/edit', 'JvscriptController@editSkin')->name('skin.edit');
Route::put('/script/{slug}/edit', 'JvscriptController@updateScript')->name('script.update');
Route::put('/skin/{slug}/edit', 'JvscriptController@updateSkin')->name('skin.update');

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


Auth::routes();

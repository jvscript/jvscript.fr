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

//==UserController==
Route::get('/', 'UserController@index')->name('index');

Route::get('/search/{keyword}', 'UserController@index')->name('search');

Route::get('/admin', 'UserController@admin')->name('admin_index')->middleware('auth');
Route::get('/admin/comments', 'UserController@adminComments')->name('admin.comments')->middleware('auth');
Route::get('/admin/comment/{comment_id}/delete', 'UserController@adminDeleteComment')->name('admin.comment.delete')->middleware('auth');

Route::get('/messcripts', 'UserController@mesScripts')->name('messcripts')->middleware('auth');

//ajax-users
Route::get('/ajax-users', 'UserController@ajaxUsers')->name('search')->middleware('auth');

//contact form
Route::get('/contact/{message_body?}', function ($message_body = null) {
    return view('contact', ['message_body' => $message_body]);
})->name('contact.form');
//contact action
Route::post('/contact', 'UserController@contactSend')->name('contact.send');


//==ScriptController==
//forms
Route::get('/script/ajout', function () {
    return view('script.form');
})->name('script.form')->middleware('auth');
Route::get('/skin/ajout', function () {
    return view('skin.form');
})->name('skin.form')->middleware('auth');

//form action (store in db)
Route::post('/script/ajout', 'ScriptController@storeScript')->name('script.store')->middleware('auth');
Route::post('/skin/ajout', 'SkinController@storeSkin')->name('skin.store')->middleware('auth');

//show 1 item
Route::get('/script/{slug}', 'ScriptController@show')->name('script.show');
Route::get('/skin/{slug}', 'SkinController@show')->name('skin.show');

//scripts comment
Route::post('/script/{slug}/comment', 'CommentController@storeComment')->name('script.comment')->middleware('auth');
Route::post('/skin/{slug}/comment', 'CommentController@storeComment')->name('skin.comment')->middleware('auth');
//delete comment
Route::get('/script/{slug}/comment/{comment_id}/delete', 'CommentController@deleteComment')->name('script.comment.delete')->middleware('auth');
Route::get('/skin/{slug}/comment/{comment_id}/delete', 'CommentController@deleteComment')->name('skin.comment.delete')->middleware('auth');


//install, note
Route::match(['get', 'post'], '/script/install/{slug}', 'ScriptController@install')->name('script.install');
Route::match(['get', 'post'], '/skin/install/{slug}', 'SkinController@install')->name('skin.install');
Route::post('/script/note/{slug}/{note}', 'ScriptController@note')->name('script.note');
Route::post('/skin/note/{slug}/{note}', 'SkinController@note')->name('skin.note');


//updates
Route::get('/script/{slug}/edit', 'ScriptController@edit')->name('script.edit')->middleware('auth');
Route::get('/skin/{slug}/edit', 'SkinController@edit')->name('skin.edit')->middleware('auth');
Route::put('/script/{slug}/edit', 'ScriptController@updateScript')->name('script.update')->middleware('auth');
Route::put('/skin/{slug}/edit', 'SkinController@updateSkin')->name('skin.update')->middleware('auth');
//delete
Route::get('/script/{slug}/delete', 'ScriptController@delete')->name('script.delete');
Route::get('/skin/{slug}/delete', 'SkinController@delete')->name('skin.delete');

//validate script/skin
Route::get('/script/{slug}/validate', 'ScriptController@validateItem')->name('script.validate');
Route::get('/skin/{slug}/validate', 'SkinController@validateItem')->name('skin.validate');
Route::get('/script/{slug}/refuse', 'ScriptController@refuse')->name('script.refuse');
Route::get('/skin/{slug}/refuse', 'SkinController@refuse')->name('skin.refuse');


//static views
Route::get('/developpeurs', function () {
    return view('statics.developpeurs');
});
Route::get('/aide', function () {
    return view('statics.comment-installer');
})->name('aide');

Route::get('/crawlInfo', 'ScriptController@crawlInfo');

Auth::routes();

Route::get('auth/github', 'Auth\LoginController@redirectToProvider');
Route::get('auth/github/callback', 'Auth\LoginController@handleProviderCallback');


/**
 * boites à idées
 */
Route::get('/boite-a-idees', 'BoxController@index')->name('box.index');

Route::get('/boite-a-idees/ajout', 'BoxController@formAjout')->name('box.form')->middleware('auth');
Route::post('/boite-a-idees/ajout', 'BoxController@storeIdea')->name('box.store')->middleware('auth');
Route::get('/boite-a-idees/{id}', 'BoxController@showIdea')->name('box.show');

Route::get('/boite-a-idees/{id}/like', 'BoxController@likeBox')->name('box.like')->middleware('auth');
Route::get('/boite-a-idees/{id}/like/{dislike}', 'BoxController@likeBox')->name('box.dislike')->middleware('auth');
//refuse
Route::get('/boite-a-idees/{id}/refuse', 'BoxController@refuseBox')->name('box.refuse')->middleware('auth');
Route::get('/boite-a-idees/{id}/validate', 'BoxController@validateBox')->name('box.validate')->middleware('auth');
Route::get('/boite-a-idees/{id}/delete', 'BoxController@deleteBox')->name('box.delete')->middleware('auth');

// comment
Route::post('/boite-a-idees/{id}/comment', 'CommentController@storeComment')->name('box.comment')->middleware('auth');
//delete comment
Route::get('/boite-a-idees/{id}/comment/{comment_id}/delete', 'CommentController@deleteComment')->name('box.comment.delete')->middleware('auth');

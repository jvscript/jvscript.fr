<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ScriptController;
use App\Http\Controllers\SkinController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\Auth\LoginController;

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
Route::get('/', [UserController::class, 'index'])->name('index');

Route::get('/search/{keyword}', [UserController::class, 'index'])->name('search');

Route::get('/admin', [UserController::class, 'admin'])->name('admin_index')->middleware('auth');
Route::get('/admin/comments', [UserController::class, 'adminComments'])->name('admin.comments')->middleware('auth');
Route::get('/admin/comment/{comment_id}/delete', [UserController::class, 'adminDeleteComment'])->name('admin.comment.delete')->middleware('auth');

Route::get('/messcripts', [UserController::class, 'mesScripts'])->name('messcripts')->middleware('auth');

//ajax-users
Route::get('/ajax-users', [UserController::class, 'ajaxUsers'])->name('search')->middleware('auth');

//contact form
Route::get('/contact/{message_body?}', function ($message_body = null) {
    return view('contact', ['message_body' => $message_body]);
})->name('contact.form');
//contact action
Route::post('/contact', [UserController::class, 'contactSend'])->name('contact.send');


//==ScriptController==
//forms
Route::get('/script/ajout', function () {
    return view('script.form');
})->name('script.form')->middleware('auth');
Route::get('/skin/ajout', function () {
    return view('skin.form');
})->name('skin.form')->middleware('auth');

//form action (store in db)
Route::post('/script/ajout', [ScriptController::class, 'storeScript'])->name('script.store')->middleware('auth');
Route::post('/skin/ajout', [SkinController::class, 'storeSkin'])->name('skin.store')->middleware('auth');

//show 1 item
Route::get('/script/{slug}', [ScriptController::class, 'show'])->name('script.show');
Route::get('/skin/{slug}', [SkinController::class, 'show'])->name('skin.show');

//scripts comment
Route::post('/script/{slug}/comment', [CommentController::class, 'storeComment'])->name('script.comment')->middleware('auth');
Route::post('/skin/{slug}/comment', [CommentController::class, 'storeComment'])->name('skin.comment')->middleware('auth');
//delete comment
Route::get('/script/{slug}/comment/{comment_id}/delete', [CommentController::class, 'deleteComment'])->name('script.comment.delete')->middleware('auth');
Route::get('/skin/{slug}/comment/{comment_id}/delete', [CommentController::class, 'deleteComment'])->name('skin.comment.delete')->middleware('auth');


//install, note
Route::match(['get', 'post'], '/script/install/{slug}', [ScriptController::class, 'install'])->name('script.install');
Route::match(['get', 'post'], '/skin/install/{slug}', [SkinController::class, 'install'])->name('skin.install');
Route::post('/script/note/{slug}/{note}', [ScriptController::class, 'note'])->name('script.note');
Route::post('/skin/note/{slug}/{note}', [SkinController::class, 'note'])->name('skin.note');


//updates
Route::get('/script/{slug}/edit', [ScriptController::class, 'edit'])->name('script.edit')->middleware('auth');
Route::get('/skin/{slug}/edit', [SkinController::class, 'edit'])->name('skin.edit')->middleware('auth');
Route::put('/script/{slug}/edit', [ScriptController::class, 'updateScript'])->name('script.update')->middleware('auth');
Route::put('/skin/{slug}/edit', [SkinController::class, 'updateSkin'])->name('skin.update')->middleware('auth');
//delete
Route::get('/script/{slug}/delete', [ScriptController::class, 'delete'])->name('script.delete');
Route::get('/skin/{slug}/delete', [SkinController::class, 'delete'])->name('skin.delete');

//validate script/skin
Route::get('/script/{slug}/validate', [ScriptController::class, 'validateItem'])->name('script.validate');
Route::get('/skin/{slug}/validate', [SkinController::class, 'validateItem'])->name('skin.validate');
Route::get('/script/{slug}/refuse', [ScriptController::class, 'refuse'])->name('script.refuse');
Route::get('/skin/{slug}/refuse', [SkinController::class, 'refuse'])->name('skin.refuse');


//static views
Route::get('/developpeurs', function () {
    return view('statics.developpeurs');
});
Route::get('/aide', function () {
    return view('statics.comment-installer');
})->name('aide');

Route::get('/crawlInfo', [ScriptController::class, 'crawlInfo']);

Auth::routes();

Route::get('auth/github', [LoginController::class, 'redirectToProvider'])->middleware('guest');
Route::get('auth/github/callback', [LoginController::class, 'handleProviderCallback'])->middleware('guest');


/**
 * boites à idées
 */
Route::get('/boite-a-idees', [BoxController::class, 'index'])->name('box.index');

Route::get('/boite-a-idees/ajout', [BoxController::class, 'formAjout'])->name('box.form')->middleware('auth');
Route::post('/boite-a-idees/ajout', [BoxController::class, 'storeIdea'])->name('box.store')->middleware('auth');
Route::get('/boite-a-idees/{id}', [BoxController::class, 'showIdea'])->name('box.show');

Route::post('/boite-a-idees/{id}/like', [BoxController::class, 'likeBox'])->name('box.like')->middleware('auth');
Route::post('/boite-a-idees/{id}/like/{dislike}', [BoxController::class, 'likeBox'])->name('box.dislike')->middleware('auth');
//refuse
Route::get('/boite-a-idees/{id}/refuse', [BoxController::class, 'refuseBox'])->name('box.refuse')->middleware('auth');
Route::get('/boite-a-idees/{id}/validate', [BoxController::class, 'validateBox'])->name('box.validate')->middleware('auth');
Route::get('/boite-a-idees/{id}/delete', [BoxController::class, 'deleteBox'])->name('box.delete')->middleware('auth');

// comment
Route::post('/boite-a-idees/{id}/comment', [CommentController::class, 'storeComment'])->name('box.comment')->middleware('auth');
//delete comment
Route::get('/boite-a-idees/{id}/comment/{comment_id}/delete', [CommentController::class, 'deleteComment'])->name('box.comment.delete')->middleware('auth');

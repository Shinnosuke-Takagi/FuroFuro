<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes();

Route::get('/', 'ArticleController@index')->name('articles.index');
Route::resource('/articles', 'ArticleController')->except(['index', 'show'])->middleware('auth');
Route::resource('/articles', 'ArticleController')->only(['show']);

Route::prefix('articles')->name('articles.')->group(function() {
  Route::put('/{article}/like', 'ArticleController@like')->name('like')->middleware('auth');
  Route::delete('/{article}/like', 'ArticleController@unlike')->name('unlike')->middleware('auth');
});

Route::get('/articles/{article}/comment', 'CommentController@create')->name('comment.create')->middleware('auth');
Route::post('/articles/{article}/comment', 'CommentController@store')->name('comment.store')->middleware('auth');

Route::get('/tags/{name}', 'TagController@show')->name('tags.show');

Route::prefix('users')->name('users.')->group(function() {
  Route::get('/{name}', 'UserController@show')->name('show');
  Route::get('/{name}/profileEdit', 'UserController@profileEdit')->name('profileEdit');
  Route::patch('/{name}/profileUpdate', 'UserController@profileUpdate')->name('profileUpdate');
  Route::get('/{name}/accountEdit', 'UserController@accountEdit')->name('accountEdit');
  Route::patch('/{name}/emailUpdate', 'UserController@emailUpdate')->name('emailUpdate');
  Route::patch('/{name}/passwordUpdate', 'UserController@passwordUpdate')->name('passwordUpdate');
  Route::get('/{name}/likes', 'UserController@likes')->name('likes');
});

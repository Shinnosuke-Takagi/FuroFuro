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
Auth::routes(['verify' => true]);

Route::get('/login/guest', 'Auth\LoginController@guestLogin')->name('login.guest');

Route::get('/verifyEmail', function() {
  return view('auth.verify');
});

Route::get('/verified', function() {
  return view('auth.verified');
})->middleware('verified');

Route::get('/', 'ArticleController@index')->name('articles.index');
Route::resource('/articles', 'ArticleController')->except(['index', 'show']);
Route::resource('/articles', 'ArticleController')->only(['show']);

Route::prefix('articles')->name('articles.')->group(function() {
  Route::put('/{article}/like', 'ArticleController@like')->name('like');
  Route::delete('/{article}/like', 'ArticleController@unlike')->name('unlike');
});

Route::get('/articles/{article}/comment', 'CommentController@create')->name('comment.create');
Route::post('/articles/{article}/comment', 'CommentController@store')->name('comment.store');

Route::get('/comments/{comment}/reply', 'ReplyController@create')->name('reply.create');
Route::post('/comments/{comment}/reply', 'ReplyController@store')->name('reply.store');

Route::get('/tags/{name}', 'TagController@show')->name('tags.show');

Route::get('/search', 'SearchController@result')->name('search');

Route::prefix('users')->name('users.')->group(function() {
  Route::get('/{name}', 'UserController@show')->name('show');
  Route::get('/{name}/likes', 'UserController@likes')->name('likes');

  Route::get('/{name}/profileEdit', 'UserController@profileEdit')->name('profileEdit');
  Route::patch('/{name}/profileUpdate', 'UserController@profileUpdate')->name('profileUpdate');

  Route::get('/{name}/accountEdit', 'UserController@accountEdit')->name('accountEdit');
  Route::patch('/{name}/emailUpdate', 'UserController@emailUpdate')->name('emailUpdate');
  Route::patch('/{name}/passwordUpdate', 'UserController@passwordUpdate')->name('passwordUpdate');
});

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

Route::get('/', ['as' => 'root', 'uses' => 'HomeController@index']);

Auth::routes();
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    //Route::get('/news', 'NewsController@show')->name('news');
    //Route::get('/news/index', 'NewsController@index')->name('news.index');
    Route::get('/profile', 'ProfilesController@show')->name('profile');
    Route::get('/profile/{id?}', 'ProfilesController@showOther')->name('other');
    Route::get('/users', 'ProfilesController@onListUsers')->name('list');
    Route::prefix('acp')->group(function(){
        Route::get('/', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
        Route::post('/', 'Auth\AdminLoginController@login')->name('admin.login.submit');
        Route::get('/dash', 'AdminController@index')->name('admin.dashboard');
        Route::resource('/users', 'Admin\UserController');
        Route::resource('/roles','Admin\RoleController');
    });
});

Route::resource('tags', 'TagController', ['only' => ['index', 'show']]);
Route::get('tags/{slug}', ['as' => 'tags.show', 'uses' => 'TagController@show']);

Route::resource('category', 'CategoryController', ['except' => 'destroy']);

Route::resource('news', 'PostController', ['except' => ['show', 'destroy']]);
Route::get('news/{id}-{slug}', ['as' => 'news.show', 'uses' => 'PostController@show']);

Route::resource('comments', 'CommentController', ['only' => 'index']);
Route::resource('user', 'UserController', ['only' => 'show']);

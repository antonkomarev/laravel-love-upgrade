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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/posts', 'Posts\Collect\Action');
Route::middleware('auth')->group(function () {
    Route::post('/likes', 'Likes\Post\Action');
    Route::delete('/likes', 'Likes\Delete\Action');
});

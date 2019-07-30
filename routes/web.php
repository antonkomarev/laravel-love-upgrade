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

Route::get('/freshPosts', 'FreshPosts\Collect\Action');
Route::get('/mostLikedPosts', 'MostLikedPosts\Collect\Action');
Route::get('/mostDislikedPosts', 'MostDislikedPosts\Collect\Action');
Route::get('/popularPosts', 'PopularPosts\Collect\Action');
Route::get('/worstPosts', 'WorstPosts\Collect\Action');
Route::get('/mostReactedPosts', 'MostReactedPosts\Collect\Action');
Route::get('/leastReactedPosts', 'LeastReactedPosts\Collect\Action');
Route::middleware('auth')->group(function () {
    Route::get('/likedPosts', 'LikedPosts\Collect\Action');
    Route::get('/dislikedPosts', 'DislikedPosts\Collect\Action');

    Route::post('/likes', 'Likes\Post\Action');
    Route::delete('/likes', 'Likes\Delete\Action');
    Route::post('/dislikes', 'Dislikes\Post\Action');
    Route::delete('/dislikes', 'Dislikes\Delete\Action');
});

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

Auth::routes();

Route::get('/', 'ChatController@index')->name('chat');
Route::get('dialogs/{id}', 'ChatController@showDialog')->name('dialog.show');

Route::post('message/store', 'ChatController@storeMessage')->name('message.store');

//----------------------------------------------------------------------------------------------------------------------

Route::get('dialog/create', 'ChatController@createDialog')->name('dialog.create');

Route::get('photo/make', 'ChatController@makePhoto')->name('photo.make');

Route::get('/home', function () {
    return view('home');
});

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


Route::resource('/', 'FileController')->parameters([
    '' => 'file'
]);

Route::post('{file}', 'CommentController@store')->name('comment.store');
Route::delete('{file}/{comment}', 'CommentController@destroy')->name('comment.destroy');

//Route::resource('{file}/comments', 'CommentController');

/*Route::resource('{file}/', 'CommentController')->only([
    'destroy', 'edit', 'update', 'store'
])->parameters([
    '' => 'comment'
])->names([
    'store' => 'comment.store',
    'destroy' => 'comment.destroy',
]);
*/



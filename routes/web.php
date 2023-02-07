<?php

use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome')->name('home');
//statuses routes
Route::get('statuses','App\Http\Controllers\StatusesController@index')->name('statuses.index');
Route::post('statuses','App\Http\Controllers\StatusesController@store')->name('statuses.store')->middleware('auth');

//statuses likes routes
Route::post('statuses/{status}/likes','App\Http\Controllers\StatusLikesController@store')->name('statuses.likes.store')->middleware('auth');
Route::delete('statuses/{status}/likes','App\Http\Controllers\StatusLikesController@destroy')->name('statuses.likes.destroy')->middleware('auth');

// Statuses comments routes
Route::post('statuses/{status}/comments', 'App\Http\Controllers\StatusCommentsController@store')->name('statuses.comments.store')->middleware('auth');

// Comments Likes routes
Route::post('comments/{comment}/likes', 'App\Http\Controllers\CommentsLikesController@store')->name('comments.likes.store')->middleware('auth');
Route::delete('comments/{comment}/likes', 'App\Http\Controllers\CommentsLikesController@destroy')->name('comments.likes.destroy')->middleware('auth');

// Users routes
Route::get('@{user}', 'App\Http\Controllers\UsersController@show')->name('users.show');

Route::auth();


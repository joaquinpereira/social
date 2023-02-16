<?php

use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome')->name('home');
//statuses routes
Route::get('statuses','App\Http\Controllers\StatusesController@index')->name('statuses.index');
Route::get('statuses/{status}','App\Http\Controllers\StatusesController@show')->name('statuses.show');
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

// Users statuses routes
Route::get('users/{user}/statuses','App\Http\Controllers\UsersStatusController@index')->name('users.statuses.index');

// Friendships routes
Route::post('friendships/{recipient}', 'App\Http\Controllers\FriendShipsController@store')->name('friendships.store')->middleware('auth');
Route::delete('friendships/{user}', 'App\Http\Controllers\FriendShipsController@destroy')->name('friendships.destroy')->middleware('auth');

// Accept Friendships routes
Route::get('friends/requests','App\Http\Controllers\AcceptFriendShipsController@index')->name('accept-friendships.index')->middleware('auth');
Route::post('accept-friendships/{sender}','App\Http\Controllers\AcceptFriendShipsController@store')->name('accept-friendships.store')->middleware('auth');
Route::delete('accept-friendships/{sender}','App\Http\Controllers\AcceptFriendShipsController@destroy')->name('accept-friendships.destroy')->middleware('auth');

// Notification routes
Route::get('notifications','App\Http\Controllers\NotificationsController@index')->name('notifications.index')->middleware('auth');

Route::auth();


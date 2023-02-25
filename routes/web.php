<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::namespace("App\Http\Controllers")->group(function(){

//statuses routes
Route::get('statuses','StatusesController@index')->name('statuses.index');
Route::get('statuses/{status}','StatusesController@show')->name('statuses.show');
Route::post('statuses','StatusesController@store')->name('statuses.store')->middleware('auth');

//statuses likes routes
Route::post('statuses/{status}/likes','StatusLikesController@store')->name('statuses.likes.store')->middleware('auth');
Route::delete('statuses/{status}/likes','StatusLikesController@destroy')->name('statuses.likes.destroy')->middleware('auth');

// Statuses comments routes
Route::post('statuses/{status}/comments', 'StatusCommentsController@store')->name('statuses.comments.store')->middleware('auth');

// Comments Likes routes
Route::post('comments/{comment}/likes', 'CommentsLikesController@store')->name('comments.likes.store')->middleware('auth');
Route::delete('comments/{comment}/likes', 'CommentsLikesController@destroy')->name('comments.likes.destroy')->middleware('auth');

// Users routes
Route::get('@{user}', 'UsersController@show')->name('users.show');
Route::put('users/{user}','UsersController@update')->name('users.update')->middleware('auth');

// Users statuses routes
Route::get('users/{user}/statuses','UsersStatusController@index')->name('users.statuses.index');

// Friends routes
Route::get('friends','FriendsController@index')->name('friends.index')->middleware('auth');

// Friendships routes
Route::get('friendships/{recipient}','FriendShipsController@show')->name('friendships.show')->middleware('auth');
Route::post('friendships/{recipient}', 'FriendShipsController@store')->name('friendships.store')->middleware('auth');
Route::delete('friendships/{user}', 'FriendShipsController@destroy')->name('friendships.destroy')->middleware('auth');

// Accept Friendships routes
Route::get('friends/requests','AcceptFriendShipsController@index')->name('accept-friendships.index')->middleware('auth');
Route::post('accept-friendships/{sender}','AcceptFriendShipsController@store')->name('accept-friendships.store')->middleware('auth');
Route::delete('accept-friendships/{sender}','AcceptFriendShipsController@destroy')->name('accept-friendships.destroy')->middleware('auth');

// Notification routes
Route::get('notifications','NotificationsController@index')->name('notifications.index')->middleware('auth');

// Read Notification routes
Route::middleware('auth')->group(function(){
    Route::post('read-notifications/{notification}','ReadNotificationsController@store')->name('read-notifications.store');
    Route::delete('read-notifications/{notification}','ReadNotificationsController@destroy')->name('read-notifications.destroy');
});

});

Route::auth();


<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
//statuses routes
Route::get('statuses','App\Http\Controllers\StatusesController@index')->name('statuses.index');
Route::post('statuses','App\Http\Controllers\StatusesController@store')->name('statuses.store')->middleware('auth');

//statuses crelikes routes
Route::post('statuses/{status}/likes','App\Http\Controllers\StatusLikesController@store')->name('statuses.likes.store')->middleware('auth');

Route::auth();


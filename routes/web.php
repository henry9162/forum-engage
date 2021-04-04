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

Route::redirect('', '/threads');

Auth::routes();

Route::post('signin', 'AuthController@postSignin')->name('login');

Route::get('home', 'HomeController@index')->name('home');

Route::get('threads', 'ThreadsController@index')->name('threads');
Route::get('threads/search', 'SearchController@show')->name('search.show');
Route::post('friends/search', 'SearchController@searchFriends');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
Route::patch('threads/{channel}/{thread}', 'ThreadsController@update')->name('threads.update');
Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy')->name('threads.destroy');
Route::post('threads', 'ThreadsController@store')->middleware('must-be-confirmed')->name('threads.store');
Route::get('threads/{channel}', 'ThreadsController@index')->name('channels');

Route::post('locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::post('delete-locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::post('pinned-threads/{thread}', 'PinnedThreadsController@store')->name('pinned-threads.store')->middleware('admin');
Route::post('delete-pinned-threads/{thread}', 'PinnedThreadsController@destroy')->name('pinned-threads.destroy')->middleware('admin');

Route::get('threads/{channel}/{thread}/replies', 'RepliesController@index')->name('replies');
Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store')->middleware('must-be-confirmed')->name('replies.store');
Route::patch('replies/{reply}', 'RepliesController@update')->name('replies.update');
Route::post('replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');

Route::post('replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

Route::post('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth')->name('threads.store');
Route::post('threads/{channel}/{thread}/delete-subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth')->name('threads.destroy');

Route::post('replies/{reply}/favorites', 'FavoritesController@store')->name('replies.favorite');
Route::post('replies/{reply}/deletefavorites', 'FavoritesController@destroy')->name('replies.unfavorite');

Route::get('profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('profiles/{user}/activity', 'ProfilesController@index')->name('activity');
Route::get('profiles/{user}/notifications', 'UserNotificationsController@index')->name('user-notifications');
Route::get('profiles/notifications/chat', 'UserNotificationsController@chatNotifications')->name('chat-notifications');
Route::post('profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy')->name('user-notification.destroy');

Route::get('register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

Route::get('api/users', 'Api\UsersController@getAllUsers')->name('api.users');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');
Route::get('api/channels', 'Api\ChannelsController@index');

Route::get('api/leaderboard', 'Api\LeaderboardController@index')->name('api.leaderboard.index');
Route::get('leaderboard', 'LeaderboardController@index')->name('leaderboard.index');

Route::group([
    'prefix' => 'admin',
    'middleware' => 'admin',
    'namespace' => 'Admin'
], function () {
    Route::get('', 'DashboardController@index')->name('admin.dashboard.index');
    Route::post('channels', 'ChannelsController@store')->name('admin.channels.store');
    Route::get('channels', 'ChannelsController@index')->name('admin.channels.index');
    Route::get('channels/create', 'ChannelsController@create')->name('admin.channels.create');
    Route::get('channels/{channel}/edit', 'ChannelsController@edit')->name('admin.channels.edit');
    Route::patch('channels/{channel}', 'ChannelsController@update')->name('admin.channels.update');
});

Route::get('chats', 'ChatController@index')->name('chats');

Route::post('getFriends', 'ChatController@getFriends');
Route::post('/session/create', 'SessionController@create');
Route::post('/session/{session}/chats', 'ChatController@chats');
Route::post('/session/{session}/read', 'ChatController@read');
Route::post('/session/{session}/clear', 'ChatController@clear');
Route::post('/session/{session}/block', 'BlockController@block');
Route::post('/session/{session}/unblock', 'BlockController@unblock');
Route::post('/send/{session}', 'ChatController@send');

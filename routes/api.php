<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/add_system_app', 'SystemAppController@addSystemApp')->middleware('secret');

Route::post('/add_message', 'MessagesController@addMessage')->middleware('secret');

Route::get('/all_messages', 'MessagesController@allMessages')->middleware('secret');

Route::delete('/delete_message/{id}', 'MessagesController@deleteMessage')->middleware('secret');

Route::post('/send_message', 'MessagesController@sendMessage')->middleware('secret');
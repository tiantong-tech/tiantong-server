<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
// 	return $request->user();
// });

Route::get('/', function () {
	return [
		'message' => '天瞳系统 v1.0'
	];
});

Route::post('/users/login/email', 'UserController@loginByEmail');
Route::post('/users/create', 'UserController@create')->middleware('auth:root');
Route::post('/users/delete', 'UserController@delete')->middleware('auth:root');

Route::middleware('auth')->group(function() {
	Route::post('/users/profile', 'UserController@getProfile');
	Route::post('/users/update',  'UserController@update');
	Route::post('/users/search', 'UserController@search');
});

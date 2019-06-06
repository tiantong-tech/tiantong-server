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

Route::post('/login/email', 'UserController@loginByEmail');

Route::middleware('auth:root')->group(function() {
	Route::post('/users/search', 'UserController@search');
	Route::post('/users/create', 'UserController@create');
	Route::post('/users/delete', 'UserController@delete');	
});

Route::middleware('auth')->group(function() {
	Route::post('person', 'PersonController@search');
	Route::post('person/update',  'PersonController@update');
});

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

Route::get('/', 'AppController@home');

Route::post('/login/email', 'PersonController@loginByEmail');
Route::post('/login/username', 'PersonController@loginByUsername');

Route::middleware('auth:root')->group(function() {
	Route::post('/users/search', 'UserController@search');
	Route::post('/users/create', 'UserController@create');
	Route::post('/users/delete', 'UserController@delete');	
});

Route::middleware('auth')->group(function() {
	Route::post('/profile', 'PersonController@getProfile');
	Route::post('/person/update',  'PersonController@update');
});

Route::post('/sale/tracks/create', 'SaleTrackController@create');

Route::middleware(['auth', 'auth.sale'])->group(function() {
	Route::post('/sale/tracks/create', 'SaleTrackController@create');
	Route::post('/sale/tracks/delete', 'SaleTrackController@delete');
	Route::post('/sale/tracks/update', 'SaleTrackController@update');
	Route::post('/sale/tracks/search', 'SaleTrackController@search');
});

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
Route::get('/api', 'AppController@home');

Route::post('/login/email', 'PersonController@loginByEmail');
Route::post('/login/username', 'PersonController@loginByUsername');

Route::middleware('auth:root,admin')->group(function() {
	Route::post('/users/search', 'UserController@search');
	Route::post('/users/create', 'UserController@create');
	Route::post('/users/delete', 'UserController@delete');	
});

Route::middleware('auth')->group(function() {
	Route::post('/user/profile', 'PersonController@getProfile');
	Route::post('/user/update',  'PersonController@update');
});

Route::post('/sale/tracks/create', 'SaleTrackController@create');

Route::post('/sale/tracks/create', 'SaleTrackController@create');
Route::middleware('auth:root,admin,sale')->group(function() {
	Route::post('/sale/tracks/delete', 'SaleTrackController@delete');
	Route::post('/sale/tracks/update', 'SaleTrackController@update');
	Route::post('/sale/tracks/search', 'SaleTrackController@search');
});

Route::post('/devices/create', 'AccessRecordController@generateDeviceKey');
Route::post('/yuchuan/access/record', 'AccessRecordController@accessRecord');

Route::middleware('auth:root,admin,sale')->group(function () {
	Route::post('/yuchuan/access/ips/search', 'AccessRecordController@getIPs');
	Route::post('/yuchuan/access/devices/search', 'AccessRecordController@getDevices');
	Route::post('/yuchuan/access/records/search', 'AccessRecordController@searchAccessRecords');
});

Route::middleware('auth:root,admin,sale')->group(function () {
	Route::post('/news/qiniu/token', 'NewsController@getUploadToken');
	Route::post('/news/search', 'NewsController@search');
	Route::post('/news/create', 'NewsController@create');
	Route::post('/news/update', 'NewsController@update');
	Route::post('/news/delete', 'NewsController@delete');
	Route::post('/news/detail', 'NewsController@find');
});

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
	Route::post('/users/get', 'UserController@getUsers');
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

  Route::post('/ip/merge', 'AccessRecordController@mergeIP');
  Route::post('/devices/clear', 'AccessRecordController@clearDevice');
});

Route::middleware('auth:root,admin,sale')->group(function () {
	Route::post('/news/qiniu/token', 'NewsController@getUploadToken');
	Route::post('/news/search', 'NewsController@search');
	Route::post('/news/create', 'NewsController@create');
	Route::post('/news/update', 'NewsController@update');
	Route::post('/news/delete', 'NewsController@delete');
	Route::post('/news/detail', 'NewsController@find');
});

Route::middleware('auth')->group(function () {
	Route::post('/files/update', 'FileController@update');
	Route::post('/files/upload/confirm', 'FileController@uploadConfirm');
});

Route::middleware('auth')->group(function () {
	Route::post('/activities/update', 'FileController@update');
});

Route::middleware('auth')->group(function () {
  Route::post('/projects/detail', 'ProjectController@detail');
  Route::post('/projects/search', 'ProjectController@search');
  Route::post('/projects/create', 'ProjectController@create');
  Route::post('/projects/update', 'ProjectController@update');
  Route::post('/projects/delete', 'ProjectController@delete');
});

Route::middleware('auth')->group(function () {
  Route::post('/design/schemas/search', 'DesignSchemaController@search');
  Route::post('/design/schemas/create', 'DesignSchemaController@create');
  Route::post('/design/schemas/update', 'DesignSchemaController@update');
  Route::post('/design/schemas/delete', 'DesignSchemaController@delete');
});

Route::middleware('auth')->group(function () {
  Route::post('/cad/drawings/search', 'CadDrawingController@search');
  Route::post('/cad/drawings/create', 'CadDrawingController@create');
  Route::post('/cad/drawings/update', 'CadDrawingController@update');
  Route::post('/cad/drawings/delete', 'CadDrawingController@delete');
});

Route::middleware('auth')->group(function () {
  Route::post('/quotations/search', 'QuotationController@search');
  Route::post('/quotations/create', 'QuotationController@create');
  Route::post('/quotations/update', 'QuotationController@update');
  Route::post('/quotations/delete', 'QuotationController@delete');
});

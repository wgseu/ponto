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

Route::get('/account/verify/{token}', 'AccountVerifyController@activateByToken');
Route::get('/sync/license/{token}', 'SyncLicenseController@callCommand');

Route::get('/loginExemplo', 'LoginController@login');
Route::post('login/google', 'LoginGoogleController@login');
Route::post('login/facebook', 'LoginFacebookController@login');

Route::get('/{page}', 'IndexController@any')->where('page', '.*');

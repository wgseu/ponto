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

use Illuminate\Support\Facades\Route;

Route::get('/account/verify/{token}', 'AccountVerifyController@activateByToken');
Route::get('/sync/license/{token}', 'SyncLicenseController@callCommand');
Route::get('/download/{path}', 'DownloadController@process')->where('path', '.*')->middleware('cors');
Route::get('/{page}', 'IndexController@any')->where('page', '.*');

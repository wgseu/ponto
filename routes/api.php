<?php

use Illuminate\Http\Resquest;

Route::post('login', 'LoginController@store');
Route::post('empresa/criar', 'EmpresaController@store');
Route::post('colaborador/criar', 'ColaboradorController@store');
Route::post('resete/senha', 'ColaboradorController@resetPass');
Route::post('ponto', 'PontoController@store');

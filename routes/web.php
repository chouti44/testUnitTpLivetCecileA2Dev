<?php

Route::post('/', 'IndexController@index')->middleware('web');
Route::get('/', 'IndexController@index')->middleware('web');

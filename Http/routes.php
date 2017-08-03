<?php

Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Access\Http\Controllers'], function()
{
    Route::resource('access', 'AccessController');
    Route::post('access/bulk', 'AccessController@bulk');
    Route::get('api/access', 'AccessController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Access\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('access', 'AccessApiController');
});

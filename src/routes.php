<?php

Route::group(['prefix' => config('clara.entity.route.prefix'), 'middleware' => config('clara.entity.route.middleware')], function()
{
    Route::resource('clara-entity', '\CeddyG\ClaraEntityGenerator\Http\Controllers\EntityController',
    [
        'only'      => ['index', 'store']
    ]);
});
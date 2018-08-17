<?php

\Route::get('/v1/wind/{zipCode}', 'Api\WindController@show')
    ->name('api.wind.show');

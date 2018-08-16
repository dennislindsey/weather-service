<?php

\Route::get('/wind/{zipCode}', 'Api\WindController@show')
    ->name('api.wind.show');

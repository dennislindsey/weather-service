<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClearWeatherCacheCommandTest extends TestCase
{
    /**
     * @test
     */
    function can_clear_weather_value_from_cache()
    {
        \Cache::put('weather:11111', ['key' => 'value'], now()->addMinutes(1));

        $this->assertEquals(['key' => 'value'], \Cache::get('weather:11111'));

        \Artisan::call('weather:clear', ['zipCode' => '11111']);

        $this->assertNull(\Cache::get('weather:11111'));

        $this->assertEquals("Weather data for 11111 cleared\n", \Artisan::output());
    }

    /**
     * @test
     */
    function cannot_clear_weather_data_with_invalid_zip_code()
    {
        \Cache::put('weather:11111', ['key' => 'value'], now()->addMinutes(1));

        $this->assertEquals(['key' => 'value'], \Cache::get('weather:11111'));

        \Artisan::call('weather:clear', ['zipCode' => 'abcde']);

        $this->assertEquals(['key' => 'value'], \Cache::get('weather:11111'));

        $this->assertEquals("Invalid zip code, must be 5 consecutive digits\n", \Artisan::output());
    }
}

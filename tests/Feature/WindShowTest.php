<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WindShowTest extends TestCase
{
    /**
     * @test
     */
    function can_get_wind_weather_data_with_valid_zip_code()
    {
        /** @var TestResponse $response */
        $response = $this->json('GET', route('api.wind.show', ['zipCode' => '89014']))
            ->assertStatus(200);

        $response->assertJsonStructure([
            'speed',
            'deg',
            'gust',
        ]);

        $this->assertTrue(is_float(data_get($response->json(), 'speed')));
        $this->assertTrue(is_int(data_get($response->json(), 'deg')));
        $this->assertTrue(is_float(data_get($response->json(), 'gust')) || is_null(data_get($response->json(), 'gust')));

        $this->assertGreaterThanOrEqual(0, data_get($response->json(), 'speed'));

        $this->assertLessThanOrEqual(360, data_get($response->json(), 'deg'));
        $this->assertGreaterThanOrEqual(0, data_get($response->json(), 'deg'));

        $this->assertGreaterThanOrEqual(0, data_get($response->json(), 'gust'));
    }

    /**
     * @test
     */
    function cannot_retrieve_wind_data_with_absent_zip_code()
    {
        $this->json('GET', route('api.wind.show', ['zipCode' => null]))
            ->assertStatus(404);
    }

    /**
     * @test
     */
    function cannot_retrieve_wind_data_with_invalid_zip_code()
    {
        $this->json('GET', route('api.wind.show', ['zipCode' => 'abcde']))
            ->assertStatus(404);
    }
}

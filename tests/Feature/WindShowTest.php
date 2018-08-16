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
    function can_get_wind_weather_data()
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
        $this->assertTrue(is_float(data_get($response->json(), 'gust')));

        $this->assertGreaterThanOrEqualTo(0, data_get($response->json(), 'speed'));

        $this->assertLessThanOrEqualTo(360, data_get($response->json(), 'deg'));
        $this->assertGreaterThanOrEqualTo(0, data_get($response->json(), 'deg'));

        $this->assertGreaterThanOrEqualTo(0, data_get($response->json(), 'gust'));
    }
}

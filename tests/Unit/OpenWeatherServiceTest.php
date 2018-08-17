<?php

namespace Tests\Unit;

use App\Contracts\WeatherServiceContract;
use App\Services\Weather\OpenWeatherCacheService;
use App\Services\Weather\OpenWeatherService;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OpenWeatherServiceTest extends TestCase
{
    private function getMockResponse(): array
    {
        return json_decode('{"coord":{"lon":-115.04,"lat":36.09},"weather":[{"id":800,"main":"Clear",
            "description":"clear sky","icon":"01n"}],"base":"stations","main":{"temp":307.75,"pressure":1015,
            "humidity":28,"temp_min":307.15,"temp_max":308.15},"visibility":16093,"wind":{"speed":4.6,"deg":230},
            "clouds":{"all":1},"dt":1534464900,"sys":{"type":1,"id":2049,"message":0.0038,"country":"US",
            "sunrise":1534510811,"sunset":1534559254},"id":420025074,"name":"Henderson","cod":200}', true);
    }

    /**
     * @test
     */
    function weather_service_contract_resolves_to_cache_decorator_by_default()
    {
        $this->assertEquals(OpenWeatherCacheService::class, get_class(app(WeatherServiceContract::class)));
    }

    /**
     * @test
     */
    function open_weather_service_returns_wind_data()
    {
        \Cache::shouldReceive('remember')
            ->once()
            ->with('weather:89014', Carbon::class, \Closure::class)
            ->andReturn($this->getMockResponse());

        /** @var array $windData */
        $windData = app(WeatherServiceContract::class)
            ->forZip('89014')
            ->wind();

        $this->assertEquals(['speed', 'deg', 'gust'], array_keys($windData));

        $this->assertEquals(4.6, data_get($windData, 'speed'));
        $this->assertEquals(230, data_get($windData, 'deg'));
        $this->assertNull(data_get($windData, 'gust'));
    }
}

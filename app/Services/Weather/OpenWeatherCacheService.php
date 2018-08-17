<?php
/**
 * User: dennis
 * Date: 8/16/18
 */

namespace App\Services\Weather;

use App\Contracts\WeatherServiceContract;

class OpenWeatherCacheService implements WeatherServiceContract
{
    /** @var OpenWeatherService */
    protected $weatherService;

    /**
     * OpenWeatherCacheService constructor.
     *
     * @param OpenWeatherService $weatherService
     */
    public function __construct(OpenWeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Sets the zip code context for the next API request
     *
     * @param string $zipCode
     * @return WeatherServiceContract
     */
    public function forZip(string $zipCode): WeatherServiceContract
    {
        $this->weatherService->forZip($zipCode);

        return $this;
    }

    /**
     * Gets the context zip code
     *
     * @return null|string
     */
    public function getZipCode(): ?string
    {
        return $this->weatherService->getZipCode();
    }

    /**
     * Gets weather data for the context zip code
     *
     * @return array
     */
    public function weather(): array
    {
        return \Cache::remember("weather:{$this->weatherService->getZipCode()}", now()->addMinutes(15), function () {
            return $this->weatherService->weather();
        });
    }

    /**
     * Gets wind data for the context zip code
     *
     * @return array
     */
    public function wind(): array
    {
        return array_replace([
            'speed' => null,
            'deg'   => null,
            'gust'  => null,
        ], data_get($this->weather(), 'wind', []));
    }
}
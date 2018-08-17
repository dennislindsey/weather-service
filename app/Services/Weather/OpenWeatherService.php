<?php
/**
 * User: dennis
 * Date: 8/16/18
 */

namespace App\Services\Weather;

use App\Contracts\WeatherServiceContract;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

/**
 * Class OpenWeatherService
 *
 * @see https://openweathermap.org/current
 * @package App\Services\Weather
 */
class OpenWeatherService implements WeatherServiceContract
{
    /** @var null|string */
    protected $zipCode = null;

    /** @var null|string */
    protected $apiKey = null;

    /**
     * OpenWeatherService constructor.
     */
    public function __construct()
    {
        $weatherConfig = config('weather');

        $this->apiKey = array_get($weatherConfig, 'apiKey');

        throw_if(is_null($this->apiKey), new \Exception);
    }

    /**
     * Sets the zip code context for the next API request
     *
     * @param string $zipCode
     * @return WeatherServiceContract
     */
    public function forZip(string $zipCode): WeatherServiceContract
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Gets the context zip code
     *
     * @return null|string
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * Gets weather data for the context zip code
     *
     * @return array
     */
    public function weather(): array
    {
        throw_if(is_null($this->zipCode), new \Exception);

        /** @var Response $apiResponse */
        $apiResponse = (new Client)
            ->request('GET', "api.openweathermap.org/data/2.5/weather?zip={$this->zipCode}&APPID={$this->apiKey}");

        throw_if($apiResponse->getStatusCode() != "200", new \Exception);

        return json_decode($apiResponse->getBody(), true) ?? [];
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
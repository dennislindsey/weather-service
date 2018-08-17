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
 * @see     https://openweathermap.org/current
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
     *
     * @throws OpenWeatherException
     */
    public function __construct()
    {
        $weatherConfig = config('weather');

        $this->apiKey = array_get($weatherConfig, 'apiKey');

        if (is_null($this->apiKey)) {
            throw new OpenWeatherException("openweathermaps.org API key is null, see config/weather.php");
        }
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
     * @throws OpenWeatherException
     */
    public function weather(): array
    {
        throw_if(is_null($this->zipCode), new OpenWeatherException("Zip code cannot be null"));

        /** @var Response $apiResponse */
        $apiResponse = (new Client)
            ->request('GET', "api.openweathermap.org/data/2.5/weather?zip={$this->zipCode}&APPID={$this->apiKey}");

        if ($apiResponse->getStatusCode() != "200") {
            throw new OpenWeatherException("Bad response from openweathermap.org", $apiResponse->getStatusCode());
        }

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
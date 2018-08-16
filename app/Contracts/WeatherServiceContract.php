<?php
/**
 * User: dennis
 * Date: 8/16/18
 */

namespace App\Contracts;

interface WeatherServiceContract
{
    /**
     * Sets the zip code context for the next API request
     *
     * @param string $zipCode
     * @return WeatherServiceContract
     */
    public function forZip(string $zipCode): WeatherServiceContract;

    /**
     * Gets the context zip code
     *
     * @return null|string
     */
    public function getZipCode(): ?string;

    /**
     * Gets weather data for the context zip code
     *
     * @return array
     */
    public function weather(): array;

    /**
     * Gets wind data for the context zip code
     *
     * @return array
     */
    public function wind(): array;
}
<?php
/**
 * User: dennis
 * Date: 8/16/18
 */

namespace App\Http\Controllers\Api;

use App\Contracts\WeatherServiceContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class WindController
 *
 * Processes responses for wind-based weather data
 *
 * @package App\Http\Controllers\Api
 */
class WindController extends Controller
{
    /** @var WeatherServiceContract */
    protected $weatherService;

    /**
     * WindController constructor.
     *
     * @param WeatherServiceContract $weatherService
     */
    public function __construct(WeatherServiceContract $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Get wind weather for a given zip code
     *
     * @param string $zipCode
     * @return Response
     */
    public function show(string $zipCode): Response
    {
        /** @var array $wind */
        $wind = $this->weatherService
            ->forZip($zipCode)
            ->wind();

        return new Response($wind);
    }
}

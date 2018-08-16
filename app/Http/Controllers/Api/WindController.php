<?php
/**
 * User: dennis
 * Date: 8/16/18
 */

namespace App\Http\Controllers\Api;

use App\Contracts\WeatherServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WindShowRequest;
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
     * @param WindShowRequest $request
     * @return Response
     */
    public function show(WindShowRequest $request): Response
    {
        $wind = $this->weatherService
            ->forZip($request->input('zipCode'))
            ->wind();

        return new Response($wind);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\NhtsaService;
use App\Transformers\NhtsaTransformer;
use Illuminate\Http\Request;

class NhtsaController extends Controller
{
    /**
     * @var NhtsaService
     */
    private $nhtsaService;
    /**
     * @var NhtsaTransformer
     */
    private $nhtsaTransformer;
    /**
     * Create a new controller instance.
     *
     * @param NhtsaService $nhtsaService
     * @param NhtsaTransformer $nhtsaTransformer
     */
    public function __construct(
        NhtsaService $nhtsaService,
        NhtsaTransformer $nhtsaTransformer
    ) {
        $this->nhtsaService = $nhtsaService;
        $this->nhtsaTransformer = $nhtsaTransformer;
    }
    /**
     * Get vehicle
     *
     * @url GET /vehicles/{year}/{manufacturer}/{model}
     *
     * @param $year
     * @param $manufacturer
     * @param $model
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function vehicles($year, $manufacturer, $model, Request $request)
    {
        $withRating = $request->has('withRating') && $request->withRating === 'true';

        $vehicles = $this->nhtsaService->getVehicles([
            'year' => $year,
            'manufacturer' => $manufacturer,
            'model' => $model
        ], $withRating);
        return response()->json($this->nhtsaTransformer->transform($vehicles, $withRating));
    }

    /**
     * Post vehicle
     *
     * @url POST /vehicles
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postVehicles(Request $request)
    {
        $vehicles = $this->nhtsaService->getVehicles([
            'year' => $request->input('modelYear'),
            'manufacturer' => $request->input('manufacturer'),
            'model' => $request->input('model')
        ]);
        return response()->json($this->nhtsaTransformer->transform($vehicles));
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\NhtsaService;
use Illuminate\Http\Request;

class NhtsaController extends Controller
{
    /**
     * @var NhtsaService
     */
    private $nhtsaService;
    /**
     * Create a new controller instance.
     *
     * @param NhtsaService $nhtsaService
     */
    public function __construct(NhtsaService $nhtsaService)
    {
        $this->nhtsaService = $nhtsaService;
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
        try {
            $vehicles = $this->nhtsaService->getVehicles($year, $manufacturer, $model);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'errorMessage' => $e->getMessage()]);
        }
        return response()->json(
            $this->formatVehicleResponse(
                $vehicles,
                $request->has('withRating') ? true : false
            ));
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
        $this->validate($request, [
            'modelYear' => 'required',
            'manufacturer' => 'required',
            'model' => 'required'
        ]);
        try {
            $vehicles = $this->nhtsaService->getVehicles($request->modelYear, $request->manufacturer, $request->model);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'errorMessage' => $e->getMessage()]);
        }
        return response()->json(
            $this->formatVehicleResponse(
                $vehicles
            ));
    }

    /*
     * Transform API response to desired structure
     *
     * If $withRating = true, append CrashRating for each result
     */
    function formatVehicleResponse($nhtsaResponse, $withRating = false){
        $result = [
            'Count' => $nhtsaResponse->Count,
            'Results' => []
        ];
        foreach ($nhtsaResponse->Results as $nhtsaResult) {
            $newItem = [
                'Description' => $nhtsaResult->VehicleDescription,
                'VehicleId' => $nhtsaResult->VehicleId
            ];
            if ($withRating) {
                $vehicleRating = $this->nhtsaService->getRating($nhtsaResult->VehicleId);
                if (count($vehicleRating->Results) > 0){
                    $newItem['CrashRating'] = $vehicleRating->Results[0]->OverallRating;
                }
            }
            $result['Results'][] = $newItem;
        }
        return $result;
    }
}

<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NhtsaController extends Controller
{
    public $nhtsaUrl = 'https://one.nhtsa.gov/webapi/api/';

    /**
     * Get vehicle
     *
     * @url GET /vehicles/{year}/{manufacturer}/{model}
     *
     * @param $year
     * @param $manufacturer
     * @param $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function vehicles($year, $manufacturer, $model)
    {
        return response()->json(
            $this->formatVehicleReponse(
                $this->getNhtsaResponse($year, $manufacturer, $model)
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
        return response()->json(
            $this->formatVehicleReponse(
                $this->getNhtsaResponse($request->modelYear, $request->manufacturer, $request->model)
            ));

    }

    function formatVehicleReponse($nhtsaResponse){
        $result = [
            'Count' => $nhtsaResponse->Count,
            'Results' => []
        ];
        foreach ($nhtsaResponse->Results as $nhtsaResult) {
            $result['Results'][] = [
                'Description' => $nhtsaResult->VehicleDescription,
                'VehicleId' => $nhtsaResult->VehicleId
            ];
        }
        return $result;
    }

    function getNhtsaResponse($year, $manufacturer, $model)
    {
        $guzzle = new Client();
        $res = $guzzle->request('GET', $this->nhtsaUrl . 'SafetyRatings'
                                       . '/modelyear/' . $year
                                       . '/make/' . $manufacturer
                                       . '/model/' . $model
                                       . '?format=json');
        if ($res->getStatusCode() != 200) {
            throw new \Exception;
        }

        return json_decode($res->getBody());
    }
}

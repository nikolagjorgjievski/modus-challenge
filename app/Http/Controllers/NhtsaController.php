<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class NhtsaController extends Controller
{
    public $nhtsaUrl = 'https://one.nhtsa.gov/webapi/api/';

    public function vehicles($year, $manufacturer, $model)
    {
        $nhtsaResponse = $this->getNhtsaResponse($year, $manufacturer, $model);
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

        return response()->json($result);
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

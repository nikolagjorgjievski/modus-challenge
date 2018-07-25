<?php

namespace App\Services;

use GuzzleHttp\Client;

class NhtsaService
{
    public $url = 'https://one.nhtsa.gov/webapi/api/';

    public function getVehicles($year, $manufacturer, $model)
    {
        return $this->request('GET', $this->url . 'SafetyRatings'
                                         . '/modelyear/' . $year
                                         . '/make/' . $manufacturer
                                         . '/model/' . $model
                                         . '?format=json');
    }

    public function getRating($vehicleId)
    {
        return $this->request('GET', $this->url . 'SafetyRatings'
                                         . '/VehicleId/' . $vehicleId
                                         . '?format=json');
    }

    private function request($method, $url)
    {
        $guzzle = new Client();
        $res = $guzzle->request($method, $url);
        if ($res->getStatusCode() != 200) {
            throw new \Exception;
        }

        return json_decode($res->getBody());
    }
}
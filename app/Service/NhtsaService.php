<?php

namespace App\Services;

use GuzzleHttp\Client;

class NhtsaService
{
    public $url = 'https://one.nhtsa.gov/webapi/api/';

    /**
     * https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/<MODEL YEAR>/make/<MANUFACTURER>/model/<MODEL>?format=json
     *
     * @param $year
     * @param $manufacturer
     * @param $model
     *
     * @return mixed
     */
    public function getVehicles($year, $manufacturer, $model)
    {
        return $this->request('GET', $this->url . 'SafetyRatings'
                                         . '/modelyear/' . $year
                                         . '/make/' . $manufacturer
                                         . '/model/' . $model
                                         . '?format=json');
    }

    /**
     * https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/<VehicleId>?format=json
     *
     * @param $vehicleId
     *
     * @return mixed
     */
    public function getRating($vehicleId)
    {
        return $this->request('GET', $this->url . 'SafetyRatings'
                                         . '/VehicleId/' . $vehicleId
                                         . '?format=json');
    }

    /**
     * @param $method
     * @param $url
     *
     * @return mixed
     * @throws \Exception
     */
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
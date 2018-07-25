<?php

namespace App\Services;

use GuzzleHttp\Client;

class NhtsaService
{
    public $url = 'https://one.nhtsa.gov/webapi/api/';

    /**
     * https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/<MODEL YEAR>/make/<MANUFACTURER>/model/<MODEL>?format=json
     *
     * @param @data
     * @param @$withRating
     *
     * @return mixed
     */
    public function getVehicles($data, $withRating = false)
    {
        $vehicles = $this->request('GET', $this->url . 'SafetyRatings'
                                         . '/modelyear/' . $data['year']
                                         . '/make/' . $data['manufacturer']
                                         . '/model/' . $data['model']
                                         . '?format=json');
        return $withRating ? $this->getVehiclesWithRating($vehicles) : $vehicles;
    }

    /**
     * https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/<VehicleId>?format=json
     *
     * @param $vehicles
     *
     * @return mixed
     */
    public function getVehiclesWithRating($vehicles)
    {
        if (count($vehicles) == 0) {
            return [];
        }
        foreach ($vehicles->Results as $vehicle) {
            $vehicleRating = $this->getRating($vehicle->VehicleId);
            if (count($vehicleRating) > 0) {
                $vehicle->CrashRating = $vehicleRating->Results[0]->OverallRating;
            }
        }
        return $vehicles;
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
        try {
            $res = $guzzle->request($method, $url);
            if ($res->getStatusCode() != 200) {
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }

        return json_decode($res->getBody());
    }
}

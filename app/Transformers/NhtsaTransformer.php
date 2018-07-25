<?php

namespace App\Transformers;

class NhtsaTransformer
{
    /**
     * @param $vehicles
     * @param bool $withRating
     *
     * @return array
     */
    public function transform($vehicles, $withRating = false)
    {
        if (!$this->isValid($vehicles)) {
            return [
                'Count' => 0,
                'Results' => [],
            ];
        }
        $result = [
            'Count' => $vehicles->Count,
            'Results' => []
        ];
        foreach ($vehicles->Results as $vehicle) {
            $newItem = [
                'Description' => $vehicle->VehicleDescription,
                'VehicleId' => $vehicle->VehicleId
            ];
            if ($withRating) {
                $newItem['CrashRating'] = $vehicle->CrashRating;
            }
            $result['Results'][] = $newItem;
        }
        return $result;
    }

    /**
     * @param $vehicles
     *
     * @return bool
     */
    private function isValid($vehicles)
    {
        return count($vehicles) > 0;
    }
}

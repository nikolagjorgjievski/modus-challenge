<?php


class VehiclesTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function testVehiclesByYearManufacturerModel()
    {
        $this->get('/vehicles/2015/Audi/A3')
             ->seeJsonEquals([
                 "Count" => 4,
                 "Results" => [
                     [
                         "Description" => "2015 Audi A3 4 DR AWD",
                         "VehicleId" => 9403
                     ],
                     [
                         "Description" => "2015 Audi A3 4 DR FWD",
                         "VehicleId" => 9408
                     ],
                     [
                         "Description" => "2015 Audi A3 C AWD",
                         "VehicleId" => 9405
                     ],
                     [
                         "Description" => "2015 Audi A3 C FWD",
                         "VehicleId" => 9406
                     ],
                 ]
             ]);

        $this->get('/vehicles/2015/Toyota/Yaris')
             ->seeJsonEquals([
                 "Count" => 2,
                 "Results" => [
                     [
                         "Description" => "2015 Toyota Yaris 3 HB FWD",
                         "VehicleId" => 9791
                     ],
                     [
                         "Description" => "2015 Toyota Yaris Liftback 5 HB FWD",
                         "VehicleId" => 9146
                     ],
                 ]
             ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testVehiclesNoResults()
    {
        $this->get('/vehicles/2015/Ford/Crown Victoria')
             ->seeJsonEquals([
                 "Count" => 0,
                 "Results" => []
             ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testVehiclesInvalidRequest()
    {
        $this->get('/vehicles/undefined/Ford/Fusion')
             ->seeJsonEquals([
                 "Count" => 0,
                 "Results" => []
             ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testVehiclesWithPostRequest()
    {
        $this->post('/vehicles', [
            "modelYear" => 2015,
            "manufacturer" => "Audi",
            "model" => "A3"
        ])
             ->seeJsonEquals([
                 "Count" => 4,
                 "Results" => [
                     [
                         "Description" => "2015 Audi A3 4 DR AWD",
                         "VehicleId" => 9403
                     ],
                     [
                         "Description" => "2015 Audi A3 4 DR FWD",
                         "VehicleId" => 9408
                     ],
                     [
                         "Description" => "2015 Audi A3 C AWD",
                         "VehicleId" => 9405
                     ],
                     [
                         "Description" => "2015 Audi A3 C FWD",
                         "VehicleId" => 9406
                     ],
                 ]
             ]);

        $this->post('/vehicles', [
            "modelYear" => 2015,
            "manufacturer" => "Toyota",
            "model" => "Yaris"
        ])
             ->seeJsonEquals([
                 "Count" => 2,
                 "Results" => [
                     [
                         "Description" => "2015 Toyota Yaris 3 HB FWD",
                         "VehicleId" => 9791
                     ],
                     [
                         "Description" => "2015 Toyota Yaris Liftback 5 HB FWD",
                         "VehicleId" => 9146
                     ],
                 ]
             ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testVehiclesWithPostInvalidRequest()
    {
        $this->post('/vehicles', [
            "manufacturer" => "Honda",
            "model"        => "Accord"
        ])
             ->seeJsonEquals([
                 "Count"   => 0,
                 "Results" => []
             ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testVehiclesWithRating()
    {
        $this->get('/vehicles/2015/Audi/A3?withRating=true')
             ->seeJsonEquals([
                 "Count" => 4,
                 "Results" => [
                     [
                         "Description" => "2015 Audi A3 4 DR AWD",
                         "VehicleId" => 9403,
                         "CrashRating" => "5"
                     ],
                     [
                         "Description" => "2015 Audi A3 4 DR FWD",
                         "VehicleId" => 9408,
                         "CrashRating" => "5"
                     ],
                     [
                         "Description" => "2015 Audi A3 C AWD",
                         "VehicleId" => 9405,
                         "CrashRating" => "Not Rated"
                     ],
                     [
                         "Description" => "2015 Audi A3 C FWD",
                         "VehicleId" => 9406,
                         "CrashRating" => "Not Rated"
                     ]
                 ]
             ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testVehiclesWithRatingNotTrue()
    {
        $this->get('/vehicles/2015/Audi/A3?withRating=false')
             ->seeJsonEquals([
                 "Count" => 4,
                 "Results" => [
                     [
                         "Description" => "2015 Audi A3 4 DR AWD",
                         "VehicleId" => 9403
                     ],
                     [
                         "Description" => "2015 Audi A3 4 DR FWD",
                         "VehicleId" => 9408
                     ],
                     [
                         "Description" => "2015 Audi A3 C AWD",
                         "VehicleId" => 9405
                     ],
                     [
                         "Description" => "2015 Audi A3 C FWD",
                         "VehicleId" => 9406
                     ]
                 ]
             ]);

        $this->get('/vehicles/2015/Audi/A3?withRating=bananas')
             ->seeJsonEquals([
                 "Count" => 4,
                 "Results" => [
                     [
                         "Description" => "2015 Audi A3 4 DR AWD",
                         "VehicleId" => 9403
                     ],
                     [
                         "Description" => "2015 Audi A3 4 DR FWD",
                         "VehicleId" => 9408
                     ],
                     [
                         "Description" => "2015 Audi A3 C AWD",
                         "VehicleId" => 9405
                     ],
                     [
                         "Description" => "2015 Audi A3 C FWD",
                         "VehicleId" => 9406
                     ]
                 ]
             ]);
    }
}

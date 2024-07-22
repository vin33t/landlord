<?php

namespace vin33t\HotelBooking;

use vin33t\HotelBooking\Contracts\BookingInterface;
use vin33t\HotelBooking\Services\StubaService;
use vin33t\HotelBooking\Services\RatehawkService;
use vin33t\HotelBooking\Services\TravellandaService;

class HotelBooking implements BookingInterface
{
    protected $services = [
        'stuba' => StubaService::class,
        'ratehawk' => RatehawkService::class,
        'travellanda' => TravellandaService::class,
    ];

    public function search(string $location, string $checkIn, string $checkOut, array $guestsArray, string $APIName = null)
    {
        if ($APIName) {
            return $this->searchSingleAPI($location, $checkIn, $checkOut, $guestsArray, $APIName);
        } else {
            return $this->searchAllAPIs($location, $checkIn, $checkOut, $guestsArray);
        }
    }

    private function searchSingleAPI(string $location, string $checkIn, string $checkOut, array $guestsArray, string $APIName)
    {
        if (!array_key_exists($APIName, $this->services)) {
            throw new \Exception("API {$APIName} not supported.");
        }

        $service = new $this->services[$APIName];
        return $service->search($location, $checkIn, $checkOut, $guestsArray);
    }

    private function searchAllAPIs(string $location, string $checkIn, string $checkOut, array $guestsArray)
    {
        $results = [];
        foreach ($this->services as $serviceClass) {
            $service = new $serviceClass;
            $results = array_merge($results, $service->search($location, $checkIn, $checkOut, $guestsArray));
        }
        return $results;
    }

    public function book(array $offerDetails, string $APIName)
    {
        if (!array_key_exists($APIName, $this->services)) {
            throw new \Exception("API {$APIName} not supported.");
        }

        $service = new $this->services[$APIName];
        return $service->book($offerDetails);
    }
}

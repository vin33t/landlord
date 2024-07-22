<?php

namespace vin33t\HotelBooking\Services;

use Illuminate\Support\Facades\Config;
use vin33t\HotelBooking\Contracts\BookingInterface;
use GuzzleHttp\Client;

class RatehawkService extends BaseService implements BookingInterface
{
    protected $keyId;
    protected $apiKey;
    protected $endpoint;
    protected $loginOrganization;
    protected $currency;


    public function __construct()
    {
        $this->keyId = Config::get('hotelbooking.ratehawk.key_id');
        $this->apiKey = Config::get('hotelbooking.ratehawk.api_key');
        $this->endpoint = Config::get('hotelbooking.ratehawk.endpoint');
        $this->loginOrganization = 'cloudtravel'; // Or retrieve it from configuration if needed
        $this->currency = 'GBP'; // Or retrieve it from configuration if needed
    }

    public function search(string $location, string $checkIn, string $checkOut, array $guestsArray)
    {
        // Implement Ratehawk API search logic here
        // Return a unified response format
    }

    public function book(array $offerDetails)
    {
        // Implement Ratehawk API booking logic here
        // Return a unified response format
    }
}

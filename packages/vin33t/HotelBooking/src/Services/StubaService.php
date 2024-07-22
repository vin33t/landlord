<?php

namespace vin33t\HotelBooking\Services;

use vin33t\HotelBooking\Contracts\BookingInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class StubaService extends BaseService implements BookingInterface
{
    protected $username;
    protected $password;
    protected $endpoint;
    protected $loginOrganization;
    protected $currency;
    protected $requiredVersion;
    protected $authority;

    public function __construct()
    {
        $this->username = Config::get('hotelbooking.stuba.username');
        $this->password = Config::get('hotelbooking.stuba.password');
        $this->endpoint = Config::get('hotelbooking.stuba.endpoint');
        $this->loginOrganization = 'cloudtravel';
        $this->currency = 'GBP';
        $this->requiredVersion = '1.28';

        // Build authority XML
        $this->authority = '<Authority>' .
            '<Org>' . $this->loginOrganization . '</Org>' .
            '<User>' . $this->username . '</User>' .
            '<Password>' . $this->password . '</Password>' .
            '<Currency>' . $this->currency . '</Currency>' .
            '<Version>' . $this->requiredVersion . '</Version>' .
            '</Authority>';
    }

    public function search(string $location, string $checkIn, string $checkOut, array $guestsArray)
    {
        // Implement Stuba API search logic here
        // Example unified response:
        return [
            [
                'hotel_name' => 'Hotel A',
                'location' => $location,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'price' => 100.00,
                'api' => 'stuba'
            ],
            // More results
        ];
    }

    public function book(array $offerDetails)
    {
        // Implement Stuba API booking logic here
        // Example unified response:
        return [
            'booking_id' => '12345',
            'status' => 'confirmed',
            'api' => 'stuba'
        ];
    }
}

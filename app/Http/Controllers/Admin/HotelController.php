<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use vin33t\HotelBooking\HotelBooking;
use vin33t\HotelBooking\Services\TravellandaService;
use function App\Http\Controllers\dd;

class HotelController extends Controller
{
    public function index()
    {
        return view('agency.hotel.index');
    }

    public function index1()
    {
        $travellandaService = new TravellandaService();
        //cities 466132, 164974

        //hotels 39033328, 1438357
        $cityIds = [117976]; // Add more city IDs as needed
        $hotelIds = []; // Add hotel IDs if needed
        $checkInDate = '2024-06-10';
        $checkOutDate = '2024-06-15';
        $rooms = [
            ['NumAdults' => 2],
            ['NumAdults' => 1, 'Children' => ['ChildAge' => [4, 6]]]
        ];
        $nationality = 'FR';
        $currency = 'GBP';
        $availableOnly = 0;

        $hotelSearchResults = $travellandaService->hotelSearch($cityIds, $hotelIds, $checkInDate, $checkOutDate, $rooms, $nationality, $currency, $availableOnly);
        dd($hotelSearchResults);
        $booking = new HotelBooking();
    }

}
